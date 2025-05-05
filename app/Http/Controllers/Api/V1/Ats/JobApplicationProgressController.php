<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ats\UpdateJobApplicationPhaseTwoRequest;
use App\Services\Ats\JobApplicationProgressService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class JobApplicationProgressController extends Controller
{
    use ApiResponse;

    private JobApplicationProgressService $jobApplicationProgress;

    public function __construct(JobApplicationProgressService $applicationProgressService)
    {
        $this->jobApplicationProgress = $applicationProgressService;
    }

    public function getAllProgressByUser()
    {
        try {
            // Call the service to get job application progress
            $progress = $this->jobApplicationProgress->getJoApplicationProgressByUser();

            // If no progress found, you can return an empty response
            if (!$progress) {
                return $this->errorResponse('No progress found.', [], 401);
            }

            // If progress found, return it
            return $this->successResponse('Job application progress fetched successfully.', [$progress], 200);
        } catch (Exception $e) {
            // Optional: You can log the error here if needed

            // Return a standard error response
            return $this->errorResponse('Something went wrong.', [$e->getMessage()], 500);
        }
    }

    public function updatePhaseTwo(UpdateJobApplicationPhaseTwoRequest $request)
    {
        $result = $this->jobApplicationProgress->updatePhase($request, 2, 3);

        return $this->successResponse($result['message'], [$result['resource']], 200);
    }
}
