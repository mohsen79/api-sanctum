<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Post::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => 'string|min:10'
        ]);
        $post = auth()->user()->posts()->create($data);
        return response()->json(['message' => $post], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        // ^ for model binding PostNotFoundException registered
        return response()->json($post, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // ^ for model binding PostNotFoundException registered
        abort_if($request->user()->cannot('owner', $post), 403, 'you are not the owner of the post');
        $data = $request->validate([
            'image' => 'required|min:10|string'
        ]);
        $post->update($data);
        return response()->json(['message' => $data], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // ^ for model binding PostNotFoundException registered
        if (Gate::any(['owner', 'admin'], $post)) {
            $post->delete();
            return response()->json(['message' => 'post deleted'], 200);
        }
        return response()->json(['error' => 'you are not owner of the post'], 403);
    }
}
