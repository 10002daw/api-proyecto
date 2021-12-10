<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\User;
use App\Http\Requests\V1\AddUserToCommunityRequest;
use App\Http\Requests\V1\RemoveUserToCommunityRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityUserController extends Controller
{
    public function addUserToCommunity(AddUserToCommunityRequest $request, Community $community, User $user)
    {
        $request->validated();

        if ($user->isPartOfCommunity($community)) {
            return response()->json(['message' => 'User has already joined this community'], 500);
        } else {
            $community->users()->attach($user, ['owner' => 0, 'admin' => 0]);
            return response()->json(['message' => 'User has joined the community']);
        }
    }

    public function removeUserToCommunity(RemoveUserToCommunityRequest $request, Community $community, User $user)
    {
        $request->validated();

        if (!$user->isPartOfCommunity($community)) {
            return response()->json(['message' => 'User does not belong to the community'], 500);
        } else {
            $community->users()->detach($user);
            return response()->json(['message' => 'User removed from the community']);
        }
    }
}
