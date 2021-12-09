<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ThreadResource;
use App\Http\Requests\V1\UpdateThreadRequest;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    /**
     * Create a new ThreadController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth.admin')->only('destroy');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Thread $thread)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->isPartOfCommunity($thread->community)) {
            return response()->json(['message' => 'You don\'t have permissions'], 403);
        }
        
        return new ThreadResource($thread);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateThreadRequest $request, Thread $thread)
    {
        $request->validated();

        $thread->title = $request->get('title');

        $res = $thread->save();

        if ($res) {
            return response()->json(['message' => 'Thread update succesfully']);
        }

        return response()->json(['message' => 'Error to update thread'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        $user = auth()->user();

        $admin = false;
        foreach ($thread->community->users as $user) {
            if ($user->id == $user->id || $user->pivot->admin == 1) {
                $admin = true;
            }
        }

        if ($user->id != $thread->community->owner->first()->id && !$admin) {
            return response()->json(['message' => 'You don\'t have permissions'], 403);
        }

        $res = $thread->delete();

        if ($res) {
            return response()->json(['message' => 'Thread delete succesfully']);
        }

        return response()->json(['message' => 'Error to delete thread'], 500);
    }
}
