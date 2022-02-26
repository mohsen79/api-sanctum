<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Trend;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function tags()
    {
        return response()->json(['tags' => Tag::all()], 200);
    }

    public function createTag(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|min:3|string|unique:tags,name'
            ],
            [
                'name.unique' => 'this tag has already created'
            ]
        );
        $tag = Tag::create($data);
        return response()->json(['tag created' => $tag], 200);
    }

    public function tagPosts(Tag $tag)
    {
        // ^ for model binding TagNotFoundException registered
        return response()->json(['posts' => $tag->posts], 200);
    }

    public function postTags(Post $post)
    {
        // ^ for model binding PostNotFoundException registered
        return response()->json(['posts' => $post->tags], 200);
    }

    public function searchPostByTags(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        $tag = Tag::where('name', 'like', "%$request->name%")->first();
        if (!$tag->posts->count()) {
            return response()->json(['error' => 'this tag does not belong to any posts'], 404);
        }
        return response()->json(['posts' => $tag->posts], 200);
    }

    public function deletePostByTag(Tag $tag)
    {
        // ^ for model binding TagNotFoundException registered
        $number = [];
        foreach ($tag->posts as $key => $post) {
            $number[$key] = $post->id;
            $post->delete();
        }
        $number = implode(",", $number);
        return response()->json(['message' => 'posts with number ' . $number . ' deleted'], 200);
    }

    public function popularTags()
    {
        // & for this action schedule has set in console/kernel.php
        //? it will truncate(delete) all recoreds in trends table after every 24 hours
        //~ for use it run : php artisan schedule:work
        $trends = Trend::orderBy('trend', 'desc')->get();
        $trendedTags = [];
        foreach ($trends as $key => $trend) {
            $trendedTags[$key] = $trend->tag;
        }
        return response()->json(['trended tags in 24 hours', $trendedTags], 200);
    }
}
