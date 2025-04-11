<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ats\JobApplicationRequest;
use App\Models\JobApplicant;
use App\Services\Ats\ApplicantRegistrationService;
use App\Traits\ApiResponse;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Http\Request;
use Exception;



class JobApplicationController extends Controller
{
    use ApiResponse;
    protected $registrations;

    public function __construct(ApplicantRegistrationService $registrations)
    {
        $this->registrations = $registrations;
    }

    public function store(JobApplicationRequest $request)
    {

        try {
            $jobApplicant = $this->registrations->create($request);
            return $this->successResponse('New Applicant registerd successfully!', $jobApplicant, 201);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to register new Applicant!', [], 500);
        }
    }

    public function verifyEmail(Request $request)
    {

        try {
            $token = $request->validate([
                'token' => 'required|string',
            ]);

            $result = $this->registrations->verifyEmail($token['token']);

            return $this->successResponse('Email succesfully verified', $result, 201);
        } catch (Exception $e) {
            return $this->errorResponse('An error occured!', ['error', $e->getMessage()], 500);
        }
    }
}
