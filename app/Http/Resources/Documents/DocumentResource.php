<?php

namespace App\Http\Resources\Documents;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array for API response.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'type'         => $this->type,
            'slug'         => $this->slug,
            'description'  => $this->description,
            'flag'         => $this->flag,
            'published_at' => $this->published_at->toIso8601String(),
            'created_at'   => $this->created_at->toIso8601String(),

            'author' => $this->whenLoaded('author', function () {
                return [
                    'id'    => $this->author->id,
                    'name'  => $this->author->user->name,
                    'email' => $this->author->user->email,
                ];
            }),

            'module' => $this->whenLoaded('modules', function () {
                return [
                    'id'      => $this->modules->id,
                    'content' => $this->modules->content,
                    'flag'    => $this->modules->flag,
                ];
            }),
        ];
    }
}
