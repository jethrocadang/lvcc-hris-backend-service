<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeCourseProgressResource extends JsonResource
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
            'employeeId' => [
                'id' => $this->employee?->id,
                'employeeId' => $this->employee?->employee_id
            ],
            'courseId' => [
                'id' => $this->course?->id,
                'title' => $this->course?->title,
                'type' => $this->course?->type,

            ],
            'moduleId' => [
                'id' => $this->module?->id,
                'title' => $this->module?->title,
                'sequenceOrder' => $this->module?->sequence_order
            ],
            'status' => $this->status,
            'watchedSeconds' => $this->watched_seconds,
            'lastPosition' => $this->last_position,
            'completionDate' => $this->completion_date,
        ];
    }
}
