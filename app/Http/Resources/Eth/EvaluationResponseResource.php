<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResponseResource extends JsonResource
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
            'evaluation_item_id' => $this->evaluation_item_id,
            'enrollment_id' => $this->enrollment_id,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'evaluation_item' => [
                'id' => $this->whenLoaded('evaluationItem')->id ?? null,
                'question' => $this->whenLoaded('evaluationItem')->question ?? null,
                'sequence_order' => $this->whenLoaded('evaluationItem')->sequence_order ?? null,
                'category_id' => $this->whenLoaded('evaluationItem')->evaluation_category_id ?? null,
            ],
            'comments' => EvaluationCommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
