<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function comment(Request $request, Post $post)
    {
        $data = $request->validate([
            'comment' => 'required'
        ]);
        $data["commentable_id"] = $post->id;
        $data["commentable_type"] = get_class($post);

        $comment = auth()->user()->comments()->create($data);
        return response()->json(['message' => $comment], 200);
    }

    public function userComments(User $user)
    {
        // ^ for model binding UserNotFoundException registered
        return response()->json(["user's comments" => $user->comments], 200);
    }

    public function postComments(Post $post)
    {
        // ^ for model binding UserNotFoundException registered
        return response()->json(["post's comments" => $post->comments], 200);
    }

    public function likeComment(Comment $comment)
    {
        if (in_array($comment->id, auth()->user()->likes->pluck('likeable_id')->toArray())) {
            foreach (auth()->user()->likes->where('likeable_id', $comment->id) as $like) {
                $like->delete();
                return response()->json(['message' => 'comment unliked'], 403);
            }
        } else {
            auth()->user()->likes()->create([
                'likeable_id' => $comment->id,
                'likeable_type' => get_class($comment)
            ]);
            return response()->json(['message' => 'comment ' . $comment->id . ' liked'], 200);
        }
    }
}
