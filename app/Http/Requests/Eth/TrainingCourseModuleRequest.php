<?php

namespace App\Http\Requests\Eth;

use Illuminate\Foundation\Http\FormRequest;

class TrainingCourseModuleRequest extends FormRequest
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
            'course_id' => 'required|exists:tenant.employee_training_courses,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'type' => 'required|in:video series,text module',
            'certificate_url' => 'nullable|file|image|mimes:jpeg,jpg,png|max:2048',
            'video_url' => 'nullable|url',
            'thumbnail_url' => 'nullable|file|image|mimes:jpeg,jpg,png|max:2048',
            'sequence_order' => 'required|integer',
            'file_content' => 'nullable|mimes:pdf|max:20480',
            'text_content' => 'nullable|string',    
            'image_content' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'expiration_date' => 'nullable|date',
        ];
    }
    
}
