<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResponseGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'category_id' => $this->resource['category_id'],
            'category_title' => $this->resource['category_title'],
            'sequence_order' => $this->resource['sequence_order'],
            'responses' => EvaluationResponseResource::collection($this->resource['responses']),
        ];
    }
}
