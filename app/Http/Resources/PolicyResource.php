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
            'effectiveAt' => $this->effective_at,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
