<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResourece;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function searchUser(Request $request)
    {
        $user = User::where('name', 'like', "%{$request->name}%")->get();
        return response()->json(['users' => $user], 200);
    }

    public function userPost(User $user)
    {
        // ^ for model binding UserNotFoundException registered
        return response()->json(['posts' => $user->posts], 200);
    }

    public function likePost(Post $post)
    {
        //todo check user only can like the post once & seperate the like by making another table
        // ^ for model binding PostNotFoundException registered
        $post->update(['like' => $post->like++]);
        return response()->json(['message' => 'post liked']);
    }

    public function searchByLike(Request $request)
    {
        $posts = Post::where('image', 'like', "%{$request->image}%")->orderBy('like', 'desc')->get();
        return response()->json(['posts' => $posts], 200);
    }

    public function follow(User $user)
    {
        //todo delete timestamps from follow table
        // ^ for model binding UserNotFoundException registered
        //if authenticated user followed someone it'll unfollow that otherwise it'll follow the user
        abort_if(auth()->user()->id == $user->id, 403, 'you can not follow youself');
        return response()->json(['follow' => auth()->user()->following()->toggle([
            'profile_id' => $user->profile->id
        ])], 200);
    }

    public function following(User $user)
    {
        // ^ for model binding UserNotFoundException registered
        foreach ($user->following as $following) {
            return response()->json(['following' => $following->pivot->profile->user], 200);
        }
    }

    public function followers(User $user)
    {
        // ^ for model binding UserNotFoundException registered
        return response()->json(['followers' => $user->profile->followers], 200);
    }
}
