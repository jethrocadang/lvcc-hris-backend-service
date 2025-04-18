<?php

namespace App\Http\Requests\Ats;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


class JobApplicationCreateRequest extends FormRequest
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
            // job_applicants table
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                // Rule::unique('job_applicants')->where(function ($query) {
                //     return $query->where('tenant_id', request()->get('tenant_id'));
                // }),
            ],

            // job_selection_options table
            'job_id' => 'required|integer',
        ];
    }
}
