<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicantInformationRequest extends FormRequest
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
            // job_applicants
            'first_name' => 'nullable|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',

            // Optional: avatar file upload handling if needed
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',

            // job_applicant_informations
            'current_address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:100',
            'locale_and_division' => 'nullable|string|max:100',
            'servant_name' => 'nullable|string|max:100',
            'servant_contact_number' => 'nullable|string|max:20',
            'date_of_baptism' => 'nullable|string|max:150',
            'church_status' => 'nullable|in:active,inactive,suspended',
            'church_commitee' => 'nullable|string|max:150',
            'educational_attainment' => 'nullable|string',
            'course_or_program' => 'nullable|string|max:150',
            'school_graduated' => 'nullable|string|max:150',
            'year_graduated' => 'nullable|string|max:150',
            'is_employed' => 'nullable|boolean',
            'current_work' => 'nullable|string',
            'last_work' => 'nullable|string',
            'can_relocate' => 'nullable|boolean',

            // File uploads (resume and TOR)
            'resume' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'transcript_of_records' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',

        ];
    }
}
