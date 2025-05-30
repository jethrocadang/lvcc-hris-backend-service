<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobInterviewSchedulingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */ public function toArray(Request $request): array
    {
        $applicant = optional($this->jobApplication->jobApplicant);

        return [
            'userId' => $this->user_id,
            'userName' => optional($this->user)->name,

            'interviewSlotId' => $this->interview_slot_id,
            'interviewTimeSlotId' => $this->interview_time_slot_id,
            'jobApplicationPhaseId' => $this->job_application_phase_id,
            'jobApplicationId' => $this->job_application_id,

            'jobApplicationPhaseTitle' => optional($this->jobApplicationPhase)->title,

            'applicantName' => trim("{$applicant->first_name} {$applicant->last_name}"),
            'applicantEmail' => $applicant->email,

            'selectedDate' => $this->selected_date,
            'selectedTime' => $this->selected_time,
            'scheduleStatus' => $this->schedule_status,
            'location' => $this->location,
            'whatToBring' => $this->what_to_bring,
        ];
    }
}
