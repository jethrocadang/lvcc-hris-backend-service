<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ats\JobApplicationRequest;
use App\Services\Ats\ApplicantRegistrationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception;

/**
 * Handles job application registration and email verification for applicants.
 */
class JobApplicationController extends Controller
{
    use ApiResponse;

    /**
     * @var ApplicantRegistrationService
     */
    protected $registrations;

    /**
     * Injects the ApplicantRegistrationService dependency.
     *
     * @param ApplicantRegistrationService $registrations
     */
    public function __construct(ApplicantRegistrationService $registrations)
    {
        $this->registrations = $registrations;
    }

    /**
     * Handles pre-application registration for a job applicant.
     * This stores initial applicant info and sends a verification email.
     *
     * @param JobApplicationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function jobPreApplication(JobApplicationRequest $request)
    {
        try {
            $jobApplicant = $this->registrations->create($request);

            return $this->successResponse(
                'New Applicant registered successfully!',
                $jobApplicant,
                201
            );
        } catch (Exception $e) {
            // Log the exception for debugging (optional)
            return $this->errorResponse(
                'Failed to register new Applicant!',
                ['error' => $e->getMessage()],
                500
            );
        }
    }

    /**
     * Verifies the applicant's email using a token sent via email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmail(Request $request)
    {
        try {
            $token = $request->validate([
                'token' => 'required|string',
            ]);

            $result = $this->registrations->verifyEmail($token['token']);

            return $this->successResponse(
                'Email successfully verified',
                $result,
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'An error occurred!',
                ['error' => $e->getMessage()],
                500
            );
        }
    }
}
