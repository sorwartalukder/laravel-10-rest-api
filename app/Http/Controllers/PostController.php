<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use  App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['author'])->paginate(5);
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $data = $request->validated();
        Post::create($data);
        return response()->json(['message' => 'Post created Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with(['author'])->where('id', $id)->first();
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, string $id)
    {
        $data = $request->validated();
        $data = array_filter($data);
        $post = Post::with(['author'])->where('id', $id)->first();
        $post->update($data);
        return response()->json(['message' => 'Post updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::with(['author'])->where('id', $id)->first();
        $post->delete();
        return response()->json(['message' => 'Post deleted Successfully']);
    }
}
