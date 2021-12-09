<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Community;
use App\Http\Resources\V1\ThreadResource;
use App\Http\Requests\V1\StoreCommunityThreadRequest;
use Illuminate\Http\Request;


class CommunityThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Community $community)
    {
        $search = '%' . $request->query('search') . '%';
        return ThreadResource::collection(
            Thread::latest()
                ->where('title', 'like', $search)
                ->where('community_id', '=', $community->id)
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
    public function store(StoreCommunityThreadRequest $request, Community $community)
    {
        $request->validated();

        $user = auth()->user();

        $thread = new Thread();

        $thread->title = $request->get('title');
        $thread->community()->associate($community->id);
        $thread->user()->associate($user);

        $res = $thread->save();

        if ($res) {
            $message = 'Thread create succesfully';
            return response()->json(compact('message', 'thread'), 201);
        }
        return response()->json(['message' => 'Error to create thread'], 500);
    }
}
