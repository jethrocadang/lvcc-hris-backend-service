<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewScheduleSlotResource extends JsonResource
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
            // 'admin' => $this->admin ? new UserResource($this->admin) : null,
            'scheduled_date' => $this->scheduled_date,
            'start_time' => $this->start_time,
            'slot_status' => $this->slot_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
