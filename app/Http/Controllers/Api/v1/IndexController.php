<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResourece;
use App\Models\Dislike;
use App\Models\Like;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Exists;

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
        // ^ for model binding PostNotFoundException registered
        if (auth()->user()->likes->where('likeable_type', get_class($post))
            ->where('likeable_id', $post->id)->count()
        ) {
            foreach (auth()->user()->likes->where('likeable_type', get_class($post))
                ->where('likeable_id', $post->id) as $like) {
                $like->delete();
            }
            return response()->json(['message' => 'post unliked'], 200);
        } else {
            auth()->user()->likes()->create([
                'likeable_id' => $post->id,
                'likeable_type' => get_class($post)
            ]);
            return response()->json(['message' => 'post ' . $post->id . ' liked'], 200);
        }
    }

    public function usersLikedPost(Post $post)
    {
        // ^ for model binding PostNotFoundException registered
        $likes = Like::all();
        $users = [];
        foreach ($likes->where('likeable_id', $post->id)->where('likeable_type', get_class($post)) as $like) {
            $users[] = $like->user;
        }
        return response()->json(['these users liked this post' => $users], 200);
    }

    public function disLikePost(Post $post)
    {
        // ^ for model binding PostNotFoundException registered
        if (auth()->user()->dislikes->where('dislikeable_type', get_class($post))
            ->where('dislikeable_id', $post->id)->count()
        ) {
            foreach (auth()->user()->dislikes->where('dislikeable_id', $post->id)
                ->where('dislikeable_type', get_class($post)) as $dislike) {
                $dislike->delete();
            }
            return response()->json(['message' => 'post undsiliked'], 403);
        } else {
            auth()->user()->dislikes()->create([
                'dislikeable_id' => $post->id,
                'dislikeable_type' => get_class($post)
            ]);
            return response()->json(['message' => 'post ' . $post->id . ' disliked'], 200);
        }
    }

    public function usersDisLikedPost(Post $post)
    {
        // ^ for model binding PostNotFoundException registered
        $dislikes = Dislike::all();
        $users = [];
        foreach ($dislikes->where('dislikeable_id', $post->id)->where('dislikeable_type', get_class($post)) as $dislike) {
            $users[] = $dislike->user;
        }
        return response()->json(['these users disliked this post' => $users], 200);
    }

    public function searchByLike(Request $request)
    {
        $posts = Post::where('image', 'like', "%{$request->image}%")->get();
        $likes = DB::table('likes')->selectRaw('count(*) as like_count,likeable_id')
            ->groupBy('likeable_id')
            ->where('likeable_type', 'App\Models\Post')
            ->orderBy('like_count', 'desc')->get();
        $popularPosts = array();
        foreach ($likes as $like) {
            foreach ($posts->where('id', $like->likeable_id) as $post) {
                $post["likes"] = $like->like_count;
                $popularPosts[] = $post;
            }
        }
        return response()->json(['popular posts' => $popularPosts], 200);
    }

    public function follow(User $user)
    {
        // ^ for model binding UserNotFoundException registered
        //if authenticated user followed someone it'll unfollow that otherwise it'll follow the user
        abort_if(auth()->user()->id == $user->id, 403, 'you can not follow youself');
        return response()->json(['follow' => auth()->user()->following()->toggle([
            'profile_id' => $user->profile->id,
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
