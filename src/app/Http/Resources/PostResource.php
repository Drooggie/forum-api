<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "post" => $this->post,
            "user_id" => $this->user_id,
            "topic_id" => $this->topic_id,
            "likes_count" => $this->likes->count(),
            "likes" => UserResource::collection($this->likes->pluck('user')),
        ];
    }
}
