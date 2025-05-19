<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobApplicantInformationRequest;
use App\Services\Ats\JobApplicationFormService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class JobApplicationFormController extends Controller
{
    use ApiResponse;

    private JobApplicationFormService $jobApplicationForm;

    public function __construct(JobApplicationFormService $jobApplicationForm)
    {
        $this->jobApplicationForm = $jobApplicationForm;
    }
    public function updateOrCreate(JobApplicantInformationRequest $request)
    {
        try {
            $jobApplicant = $this->jobApplicationForm->update($request);
            return $this->successResponse('Job Applicant Information updated!', [$jobApplicant], 201);
        } catch (Exception $e) {
            return $this->errorResponse('Update Failed', ['error' => $e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $jobApplication = $this->jobApplicationForm->getApplicationById($id);

            return $this->successResponse('Job Applicant retrieved successfully', [$jobApplication], 200);
        } catch (Exception $e) {
            return $this->errorResponse("Failed to retrieve job applicant", [$e->getMessage()], 500);
        }
    }
}
