<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationFormResource extends JsonResource
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
            'trainingCourse' => [
                'id' => $this->trainingCourse?->id,
                'title' => $this->trainingCourse?->title,
            ],
            'title' => $this->title,
            'isActive' => $this->is_active,
            'categories' => $this->when($this->relationLoaded('categories'), function () {
                return $this->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'title' => $category->title,
                        'sequenceOrder' => $category->sequence_order,
                        'items' => $category->when($category->relationLoaded('items'), function () use ($category) {
                            return $category->items->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'question' => $item->question,
                                    'sequenceOrder' => $item->sequence_order,
                                ];
                            });
                        }),
                    ];
                });
            }),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
