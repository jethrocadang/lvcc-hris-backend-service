<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPolicyAgreementRequest extends FormRequest
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
            // TODO uncomment this lines when both tables are set
            // 'employee_id' => 'nullable|exists:employees,id|unique:user_policy_agreements,employee_id',
            // 'job_applicant_id' => 'nullable|exists:job_applicants,id|unique:user_policy_agreements,job_applicant_id',
            // 'policy_version_id' => 'required|exists:policy_versions,id',
            'employee_id' => 'nullable|exists:employees,id|unique:user_policy_agreements,employee_id',
            'job_applicant_id' => 'nullable|exists:job_applicants,id|unique:user_policy_agreements,job_applicant_id',
            'policy_version_id' => 'required|exists:policy_versions,id',
        ];
    }
}
