<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostResource;
use App\Http\Requests\V1\StoreThreadPostRequest;
use App\Models\Post;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Thread $thread)
    {
        $search = '%' . $request->query('search') . '%';
        return PostResource::collection(
            Post::latest()
                ->where('text', 'like', $search)
                ->where('thread_id', '=', $thread->id)
                ->paginate()
                ->appends(request()->query())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreThreadPostRequest $request, Thread $thread)
    {
        $request->validated();

        $user = auth()->user();

        $post = new Post();

        $post->text = $request->get('text');

        if (!empty($request->get('image'))) {
            $post->image = $request->get('image');
        }

        $post->thread()->associate($thread);
        $post->user()->associate($user);
        
        $res = $post->save();

        if ($res) {
            $message = 'Post create succesfully';
            return response()->json(compact('message', 'post'), 201);
        }
        return response()->json(['message' => 'Error to create post'], 500);
    }
}
