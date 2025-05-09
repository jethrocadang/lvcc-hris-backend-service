<?php

namespace App\Http\Requests\Ats;

use Illuminate\Foundation\Http\FormRequest;

class JobPostGetRequest extends FormRequest
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
            'filter.title' => ['sometimes', 'string', 'max:100'],
            'filter.work_type' => ['sometimes', 'in:full-time,part-time,internship'],
            'filter.job_type' => ['sometimes', 'in:onsite,remote,hybrid'],
            'filter.status' => ['sometimes', 'in:open,closed'],
            'sort' => ['sometimes', 'in:created_at,title,-created_at,-title'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ];
    }
}
