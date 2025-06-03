<?php

namespace App\Http\Requests\Ats;

use Illuminate\Foundation\Http\FormRequest;

class JobPostRequest extends FormRequest
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
            'work_type' => 'required|in:full-time,part-time,internship',
            'job_type' => 'required|in:onsite,remote,hybrid',
            'title' => 'required|string|max:40',
            'description' => 'required|string',
            'icon_name' => 'nullable|string',
            'status' => 'required|in:open,closed',
            'location' => 'nullable|string',
            'category' => 'required|in:teaching,non-teaching',
        ];
    }
}
