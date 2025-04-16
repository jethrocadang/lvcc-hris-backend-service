<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeInformationResource extends JsonResource
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
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'date_hired' => $this->date_hired,
            'contact_number' => $this->contact_number,
            'current_address' => $this->current_address,
            'permanent_address' => $this->permanent_address,
            'birth_date' => $this->birth_date,
            'baptism_date' => $this->baptism_date,
            'religion' => $this->religion,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'work_email' => $this->work_email,
            'personal_email' => $this->personal_email,
        ];
    }
}
