<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Dislike;
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
        // ^ for model binding CommentNotFoundException registered
        if (auth()->user()->likes->where('likeable_type', get_class($comment))
            ->where('likeable_id', $comment->id)->count()
        ) {
            foreach (auth()->user()->likes->where('likeable_id', $comment->id)
                ->where('likeable_type', get_class($comment)) as $like) {
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

    public function usersLikedComment(Comment $comment)
    {
        // ^ for model binding CommentNotFoundException registered
        $likes = Like::all();
        $users = [];
        foreach ($likes->where('likeable_id', $comment->id)->where('likeable_type', get_class($comment)) as $like) {
            $users[] = $like->user;
        }
        return response()->json(['these users liked this comment' => $users], 200);
    }

    public function disLikeComment(Comment $comment)
    {
        // ^ for model binding CommentNotFoundException registered
        if (auth()->user()->dislikes->where('dislikeable_type', get_class($comment))
            ->where('dislikeable_id', $comment->id)->count()
        ) {
            foreach (auth()->user()->dislikes->where('dislikeable_id', $comment->id)
                ->where('dislikeable_type', get_class($comment)) as $dislike) {
                $dislike->delete();
            }
            return response()->json(['message' => 'comment undsiliked'], 403);
        } else {
            auth()->user()->dislikes()->create([
                'dislikeable_id' => $comment->id,
                'dislikeable_type' => get_class($comment)
            ]);
            return response()->json(['message' => 'comment ' . $comment->id . ' disliked'], 200);
        }
    }

    public function usersDisLikedComment(Comment $comment)
    {
        // ^ for model binding CommentNotFoundException registered
        $dislikes = Dislike::all();
        $users = [];
        foreach ($dislikes->where('dislikeable_id', $comment->id)->where('likeable_type', get_class($comment)) as $dislike) {
            $users[] = $dislike->user;
        }
        return response()->json(['these users disliked this comment' => $users], 200);
    }
}
