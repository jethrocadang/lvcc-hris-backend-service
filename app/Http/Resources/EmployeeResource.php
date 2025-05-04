<?php

namespace App\Http\Resources;

use App\Http\Resources\DepartmentJobPositionResource;
use App\Http\Resources\EmployeeInformationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'userId' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'avatarUrl' => $this->user?->avatar_url,
            ],
            'departmentPosition' => [
                'id' => $this->departmentJobPosition?->id,
                'department' => [
                    'id' => $this->departmentJobPosition?->department?->id,
                    'department' => $this->departmentJobPosition?->department?->name,
                ],
                'position' => [
                    'id' => $this->departmentJobPosition?->position?->id,
                    'title' => $this->departmentJobPosition?->position?->title,
                ]
            ],
            'employeeInformation' => new EmployeeInformationResource($this->employeeInformation),
            'employeeId' => $this->employee_id,
            'employeeType' => $this->employee_type,
            'employmentStatus' => $this->employment_status,
            'employmentCategory' => $this->employment_category,
            'employeeStatus' => $this->employee_status,
            'employmentEndDate' => $this->employment_end_date,
            'latestPositionDesignation' => $this->latest_position_designation,
            'workSchedule' => $this->work_schedule,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
