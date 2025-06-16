<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingRequestResource extends JsonResource
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
                'employeeId' => $this->employee?->employee_id,
                'department' => [
                    'id' => $this->employee?->departmentJobPosition?->department?->id,
                    'name' => $this->employee?->departmentJobPosition?->department?->name,
                ]
            ],
            'supervisorId' => [
                'id' => $this->supervisor?->id,
                'name' => $this->supervisor?->name,
            ],
            'officerId' => [
                'id' => $this->officer?->id,
                'name' => $this->officer?->name,
            ],
            'subject' => $this->subject,
            'description' => $this->description,
            'justification' => $this->justification,
            'expectedOutcome' => $this->expected_outcome,
            'trainingFormat' => $this->training_format,
            'estimatedDuration' => $this->estimated_duration,
            'supervisorStatus' => $this->supervisor_status,
            'supervisorReviewedAt' => $this->supervisor_reviewed_at,
            'officerStatus' => $this->officer_status,
            'officerReviewedAt' => $this->officer_reviewed_at,
            'requestStatus' => $this->request_status,
            'rejectionReason' => $this->rejection_reason,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
