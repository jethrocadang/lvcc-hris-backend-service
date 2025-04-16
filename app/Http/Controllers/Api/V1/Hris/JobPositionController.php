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
    use ApiResponse; // Trait for handling API responses
    private JobPositionService $jobPositionService; // Service for job position operations

    /**
     * JobPositionController constructor.
     *
     * @param JobPositionService $jobPositionService - Injected service for job position management.
     */
    public function __construct(JobPositionService $jobPositionService)
    {
        $this->jobPositionService = $jobPositionService;
    }

    /**
     * Retrieve all job positions.
     *
     * @return JsonResponse - JSON response with job positions data or an error message.
     */
    public function index(): JsonResponse
    {
        // Fetch all job positions from the service
        $jobPositions = $this->jobPositionService->getJobPositions();

        // Return success response if data exists, otherwise return error response
        return $jobPositions->isNotEmpty()
            ? $this->successResponse('Job positions retrieved successfully!', $jobPositions)
            : $this->errorResponse('No job positions found.', [], 404);
    }

    /**
     * Retrieve a specific job position by ID.
     *
     * @param int $id - ID of the job position to retrieve.
     * @return JsonResponse - JSON response with job position data or error message.
     */
    public function show(int $id): JsonResponse
    {
        try {
            // Fetch job position by ID from the service
            $jobPosition = $this->jobPositionService->getJobPositionById($id);

            return $this->successResponse('Job position retrieved successfully!', $jobPosition);
        } catch (ModelNotFoundException $e) {
            // Handle case where job position is not found
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            // Handle any other errors during retrieval
            return $this->errorResponse('Failed to retrieve job position!', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new job position.
     *
     * @param JobPositionRequest $request - Validated request data for job position creation.
     * @return JsonResponse - JSON response with created job position data or error message.
     */
    public function store(JobPositionRequest $request): JsonResponse
    {
        try {
            // Attempt to create a new job position
            $jobPosition = $this->jobPositionService->createJobPosition($request);

            return $this->successResponse('Job position created successfully!', $jobPosition, 201);
        } catch (Exception $e) {
            // Handle any errors that occur during creation
            return $this->errorResponse('An error occurred while creating the job position.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing job position.
     *
     * @param JobPositionRequest $request - Validated request data for job position update.
     * @param int $id - ID of the job position to update.
     * @return JsonResponse - JSON response with updated job position data or error message.
     */
    public function update(JobPositionRequest $request, int $id): JsonResponse
    {
        try {
            // Attempt to update the job position with provided ID
            $jobPosition = $this->jobPositionService->updateJobPosition($request, $id);

            return $jobPosition
                ? $this->successResponse('Job position updated successfully!', $jobPosition)
                : $this->errorResponse('Failed to update job position.', [], 500);
        } catch (ModelNotFoundException $e) {
            // Handle case where job position is not found
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            // Handle any other errors during update
            return $this->errorResponse('An error occurred while updating the job position.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a job position.
     *
     * @param int $id - ID of the job position to delete.
     * @return JsonResponse - JSON response indicating success or failure.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            // Attempt to delete the job position
            $this->jobPositionService->deleteJobPosition($id);

            return $this->successResponse('Job position deleted successfully!', []);
        } catch (ModelNotFoundException $e) {
            // Handle case where job position is not found
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            // Handle any other errors during deletion
            return $this->errorResponse('An error occurred while deleting the job position.', ['error' => $e->getMessage()], 500);
        }
    }
}
