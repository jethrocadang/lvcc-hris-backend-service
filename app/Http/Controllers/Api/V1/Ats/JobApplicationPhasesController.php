<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ats\JobPhaseRequest;
use App\Services\Ats\JobApplicationPhasesService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception;

class JobApplicationPhasesController extends Controller
{
    use ApiResponse;

    protected JobApplicationPhasesService $jobApplicationPhasesService;

    public function __construct(JobApplicationPhasesService $jobApplicationPhasesService)
    {
        $this->jobApplicationPhasesService = $jobApplicationPhasesService;
    }

    /**
     * Get all job application phases
     */
    public function index()
    {
        try {
            $phases = $this->jobApplicationPhasesService->getJobApplicationPhases();

            return $this->successResponse('Job application phases retrieved successfully', $phases, 200);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve job application phases', [$e->getMessage()], 500);
        }
    }

    /**
     * Update a job application phase by ID
     */
    public function update(JobPhaseRequest $request, int $id)
    {
        try {
            $validated = $request->validated();
            
            $updatedPhase = $this->jobApplicationPhasesService->updateJobApplicationPhase($id, $validated);

            return $this->successResponse('Job application phase updated successfully', [$updatedPhase], 200);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update job application phase', [$e->getMessage()], 500);
        }
    }
}
