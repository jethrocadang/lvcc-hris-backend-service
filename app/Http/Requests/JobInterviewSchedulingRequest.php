<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobInterviewSchedulingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'interview_slot_id' => 'required|integer|exists:interview_schedule_slots,id',
            'interview_time_slot_id' => 'required|integer|exists:interview_schedule_time_slots,id',
            'job_application_phase_id' => 'required|integer',
            'job_application_id' => 'sometimes|integer',
            'selected_date' => 'required|date',
            'selected_time' => 'required|date_format:H:i:s',
            'schedule_status' => 'required|string',
            'location' => 'nullable|string|',
            'what_to_bring' => 'nullable|string',

        ];
    }
}
