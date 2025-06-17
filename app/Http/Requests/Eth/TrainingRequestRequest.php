<?php

namespace App\Http\Requests\Eth;

use Illuminate\Foundation\Http\FormRequest;

class TrainingRequestRequest extends FormRequest
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
            'employee_id' => 'nullable|exists:employees,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'officer_id' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'justification' => 'required|string',
            'expected_outcome' => 'required|string',
            'training_format' => 'required|string',
            'estimated_duration' => 'required|string',
            'supervisor_status' => 'in:pending,approved,rejected',
            'officer_status' => 'in:pending,approved,rejected',
            'request_status' => 'in:pending,approved,rejected',
            'supervisor_reviewed_at' => 'nullable|date',
            'officer_reviewed_at' => 'nullable|date',
        ];
    }
}
