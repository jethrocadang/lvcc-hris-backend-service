<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingCourseEnrollmentResource extends JsonResource
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
            'courseId' => [
                'id' => $this->course?->id,
                'title' => $this->course?->title
            ],
            'employeeId' => [
                'id' => $this->employee?->id,
                'employeeId' => $this->employee?->employee_id
            ],
            'enrollmentDate' => $this->enrollment_date,
            'status' => $this->status
        ];
    }
}
