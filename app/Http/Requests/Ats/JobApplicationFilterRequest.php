<?php

namespace App\Http\Requests\Ats;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'filter.phase' => ['sometimes', 'string', 'max:100'],
            'filter.name' => ['sometimes', 'string', 'max:100'],
            'filter.status' => ['sometimes', 'in:pending,in-progress,on-hold,interview-scheduled,accepted,rejected'],
            'filter.email' => ['sometimes', 'string', 'email'],
            'filter.emailVerified' => ['sometimes', 'string',],
            'sort' => ['sometimes', 'in:created_at,-created_at,name,-name'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
