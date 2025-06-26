<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EvaluationResponseGroupCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = EvaluationResponseGroupResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'form_id' => $this->resource->first()['form_id'] ?? null,
                'form_title' => $this->resource->first()['form_title'] ?? null,
                'course_id' => $this->resource->first()['course_id'] ?? null,
                'course_title' => $this->resource->first()['course_title'] ?? null,
            ],
        ];
    }
}
