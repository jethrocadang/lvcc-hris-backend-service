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
            'employee_id' => 'required|exists:employees,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'officer_id' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'supervisor_status' => 'in:pending,approved,rejected',
            'officer_status' => 'in:pending,approved,rejected',
            'request_status' => 'in:pending,approved,rejected',
            'supervisor_reviewed_at' => 'nullable|date',
            'officer_reviewed_at' => 'nullable|date',
        ];
    }
}
