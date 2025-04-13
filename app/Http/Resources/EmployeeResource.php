<?php

namespace App\Http\Resources;

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
            'user_id' => $this->user_id,
            'department_position_id' => $this->department_position_id,
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
