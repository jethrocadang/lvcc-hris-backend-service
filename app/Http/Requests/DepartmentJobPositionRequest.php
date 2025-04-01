<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentJobPositionRequest extends FormRequest
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
            "department_id" => ["required", "integer", "exists:departments,id"],
            "job_position_id" => [
                "required",
                "integer",
                "exists:job_positions,id",
                function ($attribute, $value, $fail) {
                    if (
                        \DB::table('department_positions')
                            ->where('department_id', $this->department_id)
                            ->where('job_position_id', $value)
                            ->exists()
                    ) {
                        $fail("This job position is already assigned to this department.");
                    }
                }
            ],
        ];
    }
}
