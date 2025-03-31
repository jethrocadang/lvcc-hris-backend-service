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

}
