<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function bookmark(Post $post)
    {
        // ^ for model binding PostNotFoundException registered
        if (in_array($post->id, auth()->user()->bookmarks()->pluck('id')->toArray())) {
            return response()->json(['error' => 'you have already bookmarked this post']);
        }
        auth()->user()->bookmarks()->toggle($post);
        return response()->json(['message' => 'post number ' . $post->id . ' bookmarked'], 200);
    }

    public function bookmarks(User $user)
    {
        // ^ for model binding UserNotFoundException registered
        return response()->json(['bookmarks' => $user->bookmarks], 200);
    }

    public function bookmarked(Post $post)
    {
        // ^ for model binding PostNotFoundException registered
        return response()->json(['bookmared' => $post->bookmarked], 200);
    }

    public function searchBookmarks(Request $request)
    {
        return response()->json([
            'bookmarks' => auth()->user()->bookmarks->where('image', 'like', $request->image)
        ], 200);
    }
}
