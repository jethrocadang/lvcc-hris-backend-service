<?php

namespace App\Services\Hris;

use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DepartmentService
{
    /**
     * Retrieve all departments.
     *
     * @return Collection
     */
    public function getDepartments(): Collection
    {
        // Make query
        $departments = Department::all();

        // Return collection if not empty else return empty collection.
        return $departments->isNotEmpty()
            ? DepartmentResource::collection($departments)->collection
            : collect();
    }

        /**
     * Get interview schedule slot by ID.
     *
     * @param int $id
     * @return DepartmentResource
     * @throws ModelNotFoundException|Exception
     */
    public function getDepartmentById(int $id): DepartmentResource
    {
        try {
            // Find the interview schedule slot by ID
            $department = Department::findOrFail($id);

            // Return the interview schedule slot
            return new DepartmentResource($department);
        } catch (ModelNotFoundException $e) {
            // Log error if not found
            Log::error('Department not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            // Log errors and return the exception
            Log::error('Department retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Create a new department.
     *
     * @param DepartmentRequest $request
     * @return DepartmentResource
     * @throws Exception
     */
    public function createDepartment(DepartmentRequest $request): DepartmentResource
    {
        try {
            // Validate then create new department
            $department = Department::create($request->validated());

            // Return created department
            return new DepartmentResource($department);
        } catch (Exception $e) {
            // Log errors and retrun the exeption
            Log::error('Department creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update a department.
     *
     * @param DepartmentRequest $request
     * @param int $id
     * @return DepartmentResource
     * @throws ModelNotFoundException|Exception
     */
    public function updateDepartment(DepartmentRequest $request, int $id): DepartmentResource
    {
        try {
            // Check if data exists
            $department = Department::findOrFail($id);

            // Throw error for silent failse (No data muatated, model errors & timestamps)
            if (!$department->update($request->validated())) {
                throw new Exception("Failed to update department.");
            }
            // Return the new department
            return new DepartmentResource($department);
        } catch (ModelNotFoundException $e) {
            // Throws error only for ID not found
            Log::warning("Department with ID {$id} not found.");
            throw new ModelNotFoundException("Department with ID {$id} not found.");
        } catch (Exception $e) {
            // Throws any error not catched at the top
            Log::error('Department update failed', ['error' => $e->getMessage()]);
            throw new Exception("An error occurred while updating the department.");
        }
    }


    /**
     * Delete a department.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException|Exception
     */
    public function deleteDepartment(int $id): bool
    {
        try {
            //Check if data exists
            $department = Department::findOrFail($id);
            // Throw error for silent fails (No data muatated, model errors & timestamps)
            if (!$department->delete()) {
                throw new Exception("Failed to delete department.");
            }

            return true;
        } catch (ModelNotFoundException $e) {
            Log::warning("Department with ID {$id} not found.");
            throw new ModelNotFoundException("Department with ID {$id} not found.");
        } catch (Exception $e) {
            Log::error('Department deletion failed', ['error' => $e->getMessage()]);
            throw new Exception("An error occurred while deleting the department.");
        }
    }


    /**
     * Retrieve all department-job position associations.
     */
    public function getAll()
    {
        return Department::with('jobPositions')->get();
    }

    /**
     * Attach a job position to a department.
     */
    public function attachJobPositionToDepartment(array $data): array
    {
        try {
            $department = Department::findOrFail($data['department_id']);
            $department->jobPositions()->attach($data['job_position_id']);

            return [
                'department_id' => $department->id,
                'job_position_id' => $data['job_position_id'],
            ];
        } catch (ModelNotFoundException $e) {
            Log::warning("Department or Job Position not found.", $data);
            throw new ModelNotFoundException('Department or Job Position not found.');
        } catch (Exception $e) {
            Log::error('Failed to attach job position to department.', ['error' => $e->getMessage()]);
            throw new Exception('Failed to attach job position to department.');
        }
    }

    /**
     * Detach a job position from a department.
     */
    public function detachJobPositionFromDepartment(int $departmentId, int $jobPositionId): bool
    {
        try {
            $department = Department::findOrFail($departmentId);
            $department->jobPositions()->detach($jobPositionId);

            return true;
        } catch (ModelNotFoundException $e) {
            Log::warning("Department or Job Position not found.", ['department_id' => $departmentId, 'job_position_id' => $jobPositionId]);
            throw new ModelNotFoundException('Department or Job Position not found.');
        } catch (Exception $e) {
            Log::error('Failed to detach job position from department.', ['error' => $e->getMessage()]);
            throw new Exception('Failed to detach job position from department.');
        }
    }
    

}
