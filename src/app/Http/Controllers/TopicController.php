<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicStoreRequest;
use App\Http\Requests\TopicUpdateRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response as FacadesResponse;

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
            "user_id" => Auth::id(),
        ]);

        $created_post = Post::create([
            "post" => $validated['post'],
            "topic_id" => $created_topic->id,
            "user_id" => Auth::id(),
        ]);

        return response()->json([
            "message" => "You successfully created Topic",
            "data" => [
                "topic" => new TopicResource($created_topic),
                "post"  => new PostResource($created_post),
            ],
        ]);
    }

    public function update(Request $request, Topic $topic): JsonResponse
    {
        Gate::authorize('update', $topic);

        $validated = $request->validate([
            "title" => "max:255",
        ]);

        $topic->title = $validated['title'];
        $topic->save();

        return response()->json([
            "message" => "You successfully updated information about Topic",
            "data" => new TopicResource($topic),
        ]);
    }

    public function destroy(Topic $topic): JsonResponse
    {
        Gate::authorize('delete', $topic);

        $topic->delete();

        return response()->json(null, 204);
    }
}
