<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostLikeStoreRequest;
use App\Http\Resources\PostResource;
use App\Models\Like;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostLikeController extends Controller
{

    public function store(Topic $topic, Post $post, Like $like): JsonResponse
    {
        Gate::authorize('like', $post);

        $post_indead = $topic->posts()->findOrFail($post->id);

        Like::create([
            "likeable_type" => $post_indead::class,
            "likeable_id" => $post_indead->id,
            "user_id" => Auth::id(),
        ]);

        return response()->json([
            "message" => "You successfully liked post",
            "data"    => new PostResource($post),
        ]);
    }

    public function destroy(Topic $topic, Post $post, Like $like): JsonResponse
    {
        $like_indead = $topic->posts()->findOrFail($post->id)->likes()->findOrFail($like->id);
        $like_indead->delete();

        return response()->json([
            "message" => "You deleted like",
            "data"    => new PostResource($post),
        ]);
    }
}
