<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Http\Requests\V1\StoreUserRequest;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new CommunityController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = '%' . $request->query('search') . '%';
        return UserResource::collection(
            User::latest()->where('name', 'like', $search)
                ->orWhere('email', 'like', $search)
                ->paginate()
                ->withQueryString()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $request->validated();

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $res = $user->save();

        if ($res) {
            $message = 'User create succesfully';
            return response()->json(compact('message', 'user'), 201);
        }
        return response()->json(['message' => 'Error to create user'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $request->validated();

        if (!empty($request->get('name'))) {
            $user->name = $request->get('name');
        }
        if (!empty($request->get('email'))) {
            $user->email = $request->get('email');
        }
        if (!empty($request->get('password'))) {
            $user->password = Hash::make($request->get('password'));
        }

        $res = $user->save();

        if ($res) {
            $message = 'User update succesfully';
            return response()->json(compact('message', 'user'), 201);
        }
        return response()->json(['message' => 'Error to update user'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $res = $user->delete();

        if ($res) {
            return response()->json(['message' => 'User delete succesfully']);
        }

        return response()->json(['message' => 'Error to delete user'], 500);
    }
}