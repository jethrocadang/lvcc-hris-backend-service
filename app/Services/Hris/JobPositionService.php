<?php

namespace App\Services\Hris;

use App\Http\Requests\JobPositionRequest;
use App\Http\Resources\JobPositionResource;
use App\Models\JobPosition;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;

class JobPositionService
{
    /**
     * Retrieve all job positions.
     *
     * @return Collection
     */
    public function getJobPositions(): Collection
    {
        //Make Query
        $jobPositions = JobPosition::all();

        // Return collection if not empty else return empty collection.
        return $jobPositions->isNotEmpty()
            ? JobPositionResource::collection($jobPositions)->collection
            : collect();
    }

    /**
     * Get job position by ID.
     *
     * @param int $id
     * @return JobPositionResource
     * @throws ModelNotFoundException|Exception
     */
    public function getJobPositionById(int $id): JobPositionResource
    {
        try {
            // Find the job position by ID
            $jobPosition = JobPosition::findOrFail($id);

            // Return the job position
            return new JobPositionResource($jobPosition);
        } catch (ModelNotFoundException $e) {
            // Log error if not found
            Log::error('Job Position not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            // Log errors and return the exception
            Log::error('Job Position retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Create a new job position.
     *
     * @param JobPositionRequest $request
     * @return JobPositionResource
     * @throws Exception
     */
    public function createJobPosition(JobPositionRequest $request): JobPositionResource
    {
        try {
            // Validate then create new Job Position
            $jobPosition = JobPosition::create($request->validated());

            // Return created job position
            return new JobPositionResource($jobPosition);
        } catch (Exception $e) {
            // Log errors then return exceptions
            Log::error('Job Position creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update an existing job position.
     *
     * @param JobPositionRequest $request
     * @param int $id
     * @return JobPositionResource
     * @throws ModelNotFoundException|Exception
     */
    public function updateJobPosition(JobPositionRequest $request, int $id): JobPositionResource
    {
        try {
            // Check if data exists
            $jobPosition = JobPosition::findOrFail($id);

            // Throw error for silent fails (No data mutated, model errors[fillables] & timestamps)
            if ($jobPosition->update($request->validated())) {
                throw new Exception("Failed to update Job Position.");
            }
            // Return new Job position
            return new JobPositionResource($jobPosition);
        } catch (ModelNotFoundException $e) {
            // Throws error only for ID not found
            Log::warning("Job Position with ID {$id} not found.");
            throw new ModelNotFoundException("Job Position with ID {$id} not found.");
        } catch (Exception $e) {
            // Throws any error not catched at the top
            Log::error('Job Position update failed', ['error' => $e->getMessage()]);
            throw new Exception('Failed to update job position.');
        }
    }

    /**
     * Delete a job position.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException|Exception
     */
    public function deleteJobPosition(int $id): bool
    {
        try {
            $jobPosition = JobPosition::findOrFail($id);

            if (!$jobPosition->delete()){
                throw new Exception("Failed to delete job position.");
            }
            return true;
        } catch (ModelNotFoundException $e) {
            Log::warning("Job Position with ID {$id} not found.");
            throw new ModelNotFoundException("Job Position with ID {$id} not found.");
        } catch (Exception $e) {
            Log::error('Job Position deletion failed', ['error' => $e->getMessage()]);
            throw new Exception('Failed to delete job position.');
        }
    }
}
