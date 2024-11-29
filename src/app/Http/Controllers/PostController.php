<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Topic $topic): JsonResponse
    {
        $posts = Post::where('topic_id', $topic->id)->paginate(10);

        $metadata = collect($posts->toArray())->except("data");

        return response()->json([
            "data" => PostResource::collection($posts),
            "meta" => $metadata,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request, Topic $topic): JsonResponse
    {
        $validated = $request->validated();

        $new_post = Post::create([
            "post" => $validated["post"],
            "user_id" => Auth::id(),
            "topic_id" => $topic->id,
        ]);

        return response()->json([
            "message" => "You successfully added post",
            "data" => new PostResource($new_post)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic, Post $post): JsonResponse
    {
        $post_data = $topic->posts()->findOrFail($post->id);

        return response()->json([
            "data" => new PostResource($post_data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic, Post $post): JsonResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            "post" => "max:3000",
        ]);

        $belongs_to_topic_post = $topic->posts()->findOrFail($post->id);
        $belongs_to_topic_post->post = $validated["post"];
        $belongs_to_topic_post->save();

        return response()->json([
            "message" => "You succesfully updated information",
            "data" => new PostResource($belongs_to_topic_post),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic, Post $post): JsonResponse
    {
        Gate::authorize('delete', $post);

        $belongs_to_topic_post = $topic->posts()->findOrFail($post->id);
        $belongs_to_topic_post->delete();

        return response()->json(null, 204);
    }
}
