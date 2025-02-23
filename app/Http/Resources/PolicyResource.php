<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
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
            'version' => $this->version,
            'content' => $this->content,
            'effective_at' => $this->effective_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
