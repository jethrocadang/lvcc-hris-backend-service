<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EmployeeResource;

class EmployeeEvaluationResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'enrollment_id' => $this->resource['enrollment_id'],
            'employee' => new EmployeeResource($this->resource['employee']),
            'course_id' => $this->resource['course_id'],
            'course_title' => $this->resource['course_title'],
            'form_id' => $this->resource['form_id'],
            'form_title' => $this->resource['form_title'],
            'submitted_at' => $this->resource['submitted_at'],
            'response_groups' => new EvaluationResponseGroupCollection($this->resource['response_groups']),
            'average_rating' => $this->resource['average_rating'],
        ];
    }
}
