<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class DocumentResource
 *
 * @package \App\Http\Resources
 *
 * @property-read bool $has_paid_semester
 */
class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'content' => $this->content,
            'url' => $this->url,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'keywords' => $this->keywords()->pluck('word')->toArray(),
            'tags' => $this->tags()->pluck('tag')->toArray(),
            'created_at' => $this->created_at->format('m/d/Y h:i A'),
            'updated_at' => $this->updated_at->format('m/d/Y h:i A')
        ];
    }
}
