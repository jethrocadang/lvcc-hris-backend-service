<?php

namespace App\Http\Requests;

<<<<<<< HEAD
use Illuminate\Validation\Rule;
=======
>>>>>>> cf452f6 ( chore[hris_db]: employee and its infos added (not-working/in-progress))
use Illuminate\Foundation\Http\FormRequest;

class EmployeeInformationRequest extends FormRequest
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
<<<<<<< HEAD
        $employeeInfoId = $this->route('id') // or wherever you pass the ID
        ?? optional($this->employee_information)->id;

=======
>>>>>>> cf452f6 ( chore[hris_db]: employee and its infos added (not-working/in-progress))
        return [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_hired' => 'required|date',
            'contact_number' => 'required|string',
            'current_address' => 'required|string',
            'permanent_address' => 'required|string',
            'birth_date' => 'required|date',
            'baptism_date' => 'nullable|date',
            'religion' => 'nullable|string',
            'gender' => 'required|in:male,female',
            'marital_status' => 'required|in:married,widowed,separated,single',
            'educational_attainment' => 'nullable|string',
            'license' => 'nullable|string',
            'tin_number' => 'nullable|string',
            'pagibig_number' => 'nullable|string',
            'sss_number' => 'nullable|string',
            'philhealth_number' => 'nullable|string',
<<<<<<< HEAD
        'work_email' => [
            'required',
            'email',
            Rule::unique('employee_informations', 'work_email')->ignore($employeeInfoId),
        ],
        'personal_email' => [
            'nullable',
            'email',
            Rule::unique('employee_informations', 'personal_email')->ignore($employeeInfoId),
        ],
=======
            'work_email' => 'required|email|unique:employee_informations,work_email',
            'personal_email' => 'nullable|email|unique:employee_informations,personal_email',
>>>>>>> cf452f6 ( chore[hris_db]: employee and its infos added (not-working/in-progress))
        ];
    }
}
