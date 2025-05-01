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
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'dateHired' => $this->date_hired,
            'contactNumber' => $this->contact_number,
            'currentAddress' => $this->current_address,
            'permanentAddress' => $this->permanent_address,
            'birthDate' => $this->birth_date,
            'baptismDate' => $this->baptism_date,
            'religion' => $this->religion,
            'gender' => $this->gender,
            'maritalStatus' => $this->marital_status,
            'workEmail' => $this->work_email,
            'personalEmail' => $this->personal_email,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
