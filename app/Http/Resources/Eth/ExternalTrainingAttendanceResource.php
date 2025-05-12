<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExternalTrainingAttendanceResource extends JsonResource
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
            'trainingType' => $this->training_type,
            'certificateUrl' => $this->certificate_url,
            'dateStarted' => $this->date_started,
            'dateCompleted' => $this->date_completed,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
