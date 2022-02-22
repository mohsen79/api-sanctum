<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResourece;
use App\Models\Post;
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
        // ^ for model binding PostNotFoundException registered
        return response()->json(['posts' => $user->posts], 200);
    }

    public function likePost(Post $post)
    {
        //todo check user only can like the post once
        // ^ for model binding PostNotFoundException registered
        $post->update(['like' => $post->like++]);
        return response()->json(['message' => 'post liked']);
    }

    public function searchByLike(Request $request)
    {
        $posts = Post::where('image', 'like', "%{$request->image}%")->orderBy('like', 'desc')->get();
        return response()->json(['posts' => $posts], 200);
    }
}
