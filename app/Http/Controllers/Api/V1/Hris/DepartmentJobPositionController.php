<?php

namespace App\Http\Controllers\Api\V1\Hris;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentJobPositionRequest;
use App\Services\Hris\DepartmentJobPositionService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class DepartmentJobPositionController extends Controller
{
    use ApiResponse;

    protected DepartmentJobPositionService $service;

    public function __construct(DepartmentJobPositionService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieve all department-job position associations.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = $this->service->getAll();
        return $this->successResponse('Department-job position associations retrieved successfully.', $data);
    }

    /**
     * Create a new department-job position association.
     *
     * @param DepartmentJobPositionRequest $request
     * @return JsonResponse
     */
    public function store(DepartmentJobPositionRequest $request): JsonResponse
    {
        try {
            $record = $this->service->attachJobPositionToDepartment($request->validated());

            return $this->successResponse('Department-job position association created successfully.', $record, 201);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Department or Job Position not found.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create department-job position association.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a department-job position association.
     *
     * @param int $departmentId
     * @param int $jobPositionId
     * @return JsonResponse
     */
    public function destroy(int $departmentId, int $jobPositionId): JsonResponse
    {
        try {
            $deleted = $this->service->detachJobPositionFromDepartment($departmentId, $jobPositionId);

            return $deleted
                ? $this->successResponse('Department-job position association deleted successfully.', [], 200)
                : $this->errorResponse('Failed to delete department-job position association.', [], 500);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Department or Job Position not found.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while deleting the department-job position association.', ['error' => $e->getMessage()], 500);
        }
    }
}
