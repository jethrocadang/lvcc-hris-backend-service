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
            'interview_slot_id' => 'sometimes|integer|exists:interview_schedule_slots,id',
            'interview_time_slot_id' => 'sometimes|integer|exists:interview_schedule_time_slots,id',
            'job_application_phase_id' => 'sometimes|integer',
            'job_application_id' => 'sometimes|integer|exists:tenant.job_applications,id',
            'interview_type' => 'sometimes|string',
            'selected_date' => 'required|date',
            'selected_time' => 'required|date_format:H:i',
            'schedule_status' => 'sometimes|string',
            'location' => 'nullable|string',
            'what_to_bring' => 'nullable|string',

        ];
    }
}
