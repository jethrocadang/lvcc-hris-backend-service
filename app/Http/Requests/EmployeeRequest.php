<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'department_position_id' => 'required|exists:department_positions,id',
            'employee_type' => 'required|in:full-time,part-time,volunteer',
            'employment_status' => 'required|in:regular,probationary',
            'employment_category' => 'required|in:teaching,non-teaching',
            'employee_status' => 'required|in:active,resigned,terminated,contract_ended,on_leave,suspended',
            'employment_end_date' => 'nullable|date',
            'latest_position_designation' => 'nullable|string',
            'work_schedule' => 'nullable|date',
        ];
    }
}
