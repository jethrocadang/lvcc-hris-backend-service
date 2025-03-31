<?php

namespace App\Http\Controllers\Api\V1\Hris;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobPositionRequest;
use App\Services\Hris\JobPositionService;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class JobPositionController
 *
 * Handles CRUD operations for Job Positions.
 */
class JobPositionController extends Controller
{
    use ApiResponse;
    private JobPositionService $jobPositionService;

    /**
     * JobPositionController constructor.
     *
     * @param JobPositionService $jobPositionService
     */
    public function __construct(JobPositionService $jobPositionService)
    {
        $this->jobPositionService = $jobPositionService;
    }

    /**
     * Retrieve all job positions.
     *
     * @return JsonResponse
     */
    public function getJobPositions(): JsonResponse
    {
        $jobPositions = $this->jobPositionService->getJobPositions();

        return $jobPositions->isNotEmpty()
            ? $this->successResponse('Job positions retrieved successfully!', $jobPositions)
            : $this->errorResponse('No job positions found.', [], 404);
    }

    /**
     * Create a new job position.
     *
     * @param JobPositionRequest $request
     * @return JsonResponse
     */
    public function createJobPosition(JobPositionRequest $request): JsonResponse
    {
        try {
            $jobPosition = $this->jobPositionService->createJobPosition($request);

            return $this->successResponse('Job position created successfully!', $jobPosition, 201);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while creating the job position.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing job position.
     *
     * @param JobPositionRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateJobPosition(JobPositionRequest $request, int $id): JsonResponse
    {
        try {
            $jobPosition = $this->jobPositionService->updateJobPosition($request, $id);

            return $jobPosition
                ? $this->successResponse('Job position updated successfully!', $jobPosition)
                : $this->errorResponse('Failed to update job position.', [], 500);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Job position not found.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while updating the job position.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a job position.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteJobPosition(int $id): JsonResponse
    {
        try {
            $this->jobPositionService->deleteJobPosition($id);

            return $this->successResponse('Job position deleted successfully!', []);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Job position not found.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while deleting the job position.', ['error' => $e->getMessage()], 500);
        }
    }
}
