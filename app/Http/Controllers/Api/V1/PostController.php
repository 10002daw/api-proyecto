<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostResource;
use App\Http\Requests\V1\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->isPartOfCommunity($post->thread->community)) {
            return response()->json(['message' => 'You don\'t have permissions'], 403);
        }
        
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $request->validated();

        if (!empty($request->get('text'))) {
            $post->text = $request->get('text');
        }
        if (!empty($request->get('image'))) {
            $post->image = $request->get('image');
        }

        $res = $post->save();

        if ($res) {
            return response()->json(['message' => 'Post update succesfully']);
        }

        return response()->json(['message' => 'Error to update post'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $user = auth()->user();
        error_log('user->id: ' . $user->id . ' post->user_id: ' . $post->user_id);

        if (!$user->isAdmin() 
            && !$user->isAdminOfCommunity($post->thread->community)
            && !$user->isOwnerOfCommunity($post->thread->community)
            && !($user->id==$post->user_id)
        ) {
            return response()->json(['message' => 'You don\'t have permissions'], 403);
        }
        
        $res = $post->delete();

        if ($res) {
            return response()->json(['message' => 'Post delete succesfully']);
        }

        return response()->json(['message' => 'Error to delete post'], 500);
    }
}
