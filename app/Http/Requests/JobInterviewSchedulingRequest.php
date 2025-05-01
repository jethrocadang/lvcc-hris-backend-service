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
            'interview_schedule_slot_id' => 'required|integer|exists:interview_schedule_slots,id',
            'selected_date' => 'required|date',
            'selected_time' => 'required|date_format:H:i:s',
            'schedule_status' => 'required|string|in:booked',
        ];
    }
}
