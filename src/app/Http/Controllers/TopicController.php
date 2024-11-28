<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicStoreRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TopicController extends Controller
{

    public function index(): JsonResponse
    {
        $topics = Topic::latest()->paginate(10);
        $metadata = collect($topics->toArray())->except('data');

        return response()->json([
            "data" => TopicResource::collection($topics),
            "meta" => $metadata
        ]);
    }

    public function show(Topic $topic): JsonResponse
    {
        $topic_data = Topic::with('posts')->findOrFail($topic->id);

        return response()->json([
            "data" => [
                "topic" => new TopicResource($topic_data),
                "posts" => PostResource::collection($topic_data->posts)
            ],
        ]);
    }

    public function store(TopicStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $created_topic = Topic::create([
            "title" => $validated['title'],
            "user_id" => $validated['user_id'],
        ]);

        $created_post = Post::create([
            "post" => $validated['post'],
            "user_id" => $validated['user_id'],
            "topic_id" => $created_topic->id
        ]);

        return response()->json([
            "message" => "You successfully created Topic",
            "data" => [
                "topic" => new TopicResource($created_topic),
                "post"  => new PostResource($created_post),
            ],
        ]);
    }
}
