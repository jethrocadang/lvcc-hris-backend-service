<?php

namespace App\Services\Hris;

use App\Models\Department;
use App\Models\JobPosition;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Exception;

class DepartmentJobPositionService
{
    /**
     * Retrieve all department-job position associations.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Department::with('jobPositions')->get();
    }

    /**
     * Attach a job position to a department.
     *
     * @param array $data
     * @return array
     * @throws ModelNotFoundException|Exception
     */
    public function attachJobPositionToDepartment(array $data): array
    {
        try {
            $department = Department::findOrFail($data['department_id']);
            $jobPosition = JobPosition::findOrFail($data['job_position_id']);

            // Attach the job position to the department
            $department->jobPositions()->attach($jobPosition);

            return [
                'department' => $department->name,
                'job_position' => $jobPosition->title,
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
     *
     * @param int $departmentId
     * @param int $jobPositionId
     * @return bool
     * @throws ModelNotFoundException|Exception
     */
    public function detachJobPositionFromDepartment(int $departmentId, int $jobPositionId): bool
    {
        try {
            $department = Department::findOrFail($departmentId);
            $jobPosition = JobPosition::findOrFail($jobPositionId);

            // Detach the job position from the department
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
