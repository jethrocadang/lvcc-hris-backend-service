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
     */
    public function toArray(Request $request): array
    {
        return [
            'interviewSlotId' => $this->interview_slot_id,
            'interviewTimeSlotId' => $this->job_application_id,
            'jobApplicationPhaseId' => $this->job_application_phase_id,
            'selectedDate' => $this->selected_date,
            'selectedTime' => $this->selected_time,
            'scheduleStatus' => $this->schedule_status,
            'location' => $this->location,
            'whatToBring' => $this->what_to_bring
        ];
    }
}
