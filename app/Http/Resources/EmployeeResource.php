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
            'user_id' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'avatar_url' => $this->user?->avatar_url,
            ],
            'department_position_id' => new DepartmentJobPositionResource($this->departmentJobPosition),
            'employee_information' => new EmployeeInformationResource($this->employeeInformation),
            'employee_id' => $this->employee_id,
            'employee_type' => $this->employee_type,
            'employment_status' => $this->employment_status,
            'employment_category' => $this->employment_category,
            'employee_status' => $this->employee_status,
            'employment_end_date' => $this->employment_end_date,
            'latest_position_designation' => $this->latest_position_designation,
            'work_schedule' => $this->work_schedule,
            'created_at' => $this->created_at,
        ];
    }
}
