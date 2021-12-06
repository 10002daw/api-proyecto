<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\V1\StoreCommunityRequest;
use App\Http\Requests\V1\UpdateCommunityRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\CommunityResource;
use Illuminate\Support\Facades\Hash;

class CommunityController extends Controller
{
    /**
     * Create a new CommunityController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = '%' . $request->query('search') . '%';
        return CommunityResource::collection(
            Community::latest()->where('name', 'like', $search)
                ->orWhere('description', 'like', $search)
                ->paginate()
                ->appends(request()->query())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\V1\StoreCommunityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommunityRequest $request)
    {
        $request->validated();

        $user = auth()->user();

        if ($user->id != $request->get('owner') && !$user->isAdmin()) {
            return response()->json(['message' => 'You don\'t have permissions'], 403);
        }

        $community = new Community();

        $community->name = $request->get('name');
        $community->description = $request->get('description');
        if ($community->private = $request->get('private')) {
            $community->password = Hash::make($request->get('password'));
        }

        $res = $community->save();

        $community->users()->attach($user, ['owner' => 1, 'admin' => 1]);

        if ($res) {
            return response()->json(['message' => 'Community create succesfully'], 201);
        }
        return response()->json(['message' => 'Error to create community'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function show(Community $community)
    {
        return new CommunityResource($community);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommunityRequest $request, Community $community)
    {
        $request->validated();

        $user = auth()->user();

        if (!empty($request->get('name'))) {
            $community->name = $request->get('name');
        }
        if (!empty($request->get('description'))) {
            $community->description = $request->get('description');
        }
        if (!empty($request->get('private'))) {
            $community->private = $request->get('private');
        }
        if (!empty($request->get('password'))) {
            $community->password = Hash::make($request->get('password'));
        }

        $res = $community->save();

        if ($res) {
            if (!empty($request->get('owner'))) {
                $owner = User::find($request->get('owner'));
                $community->users()->detach();
                $community->users()->attach($owner, ['owner' => 1, 'admin' => 1]);
            }
            return response()->json(['message' => 'Community update succesfully']);
        }

        return response()->json(['message' => 'Error to update community'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function destroy(Community $community)
    {
        $res = $community->delete();

        if ($res) {
            return response()->json(['message' => 'Community delete succesfully']);
        }

        return response()->json(['message' => 'Error to update community'], 500);
    }
}
