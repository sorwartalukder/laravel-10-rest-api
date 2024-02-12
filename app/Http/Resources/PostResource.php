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
            'post_id' => $this->id,
            'title' => $this->title,
            'post_content' => $this->content,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'author' => $this->whenLoaded('author', function () {
                return new UserResource($this->author);
            }),
        ];
    }
}
