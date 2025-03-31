<?php

namespace App\Http\Controllers\Api\V1\Hris;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Services\Hris\DepartmentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Exception;

class DepartmentController extends Controller
{
    use ApiResponse;

    private DepartmentService $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function getDepartments()
    {
        $departments = $this->departmentService->getDepartments();

        return $departments->isNotEmpty()
            ? $this->successResponse('Departments retrieved successfully!', $departments)
            : $this->errorResponse('No departments found', [], 404);
    }
    public function createDepartment(DepartmentRequest $request): JsonResponse
    {
        try {

            $department = $this->departmentService->createDepartment($request);
            return $this->successResponse('Department created successfully!', $department, 201);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while creating the department.', ['error' => $e->getMessage()], 500);
        }
    }


    public function updateDepartment(DepartmentRequest $request, int $id): JsonResponse
    {
        try {
            $department = $this->departmentService->updateDepartment($request, $id);
            return $this->successResponse('Department updated successfully!', $department);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update department!', ['error' => $e->getMessage()], 500);
        }
    }


    public function deleteDepartment(int $id): JsonResponse
    {
        try {
            $this->departmentService->deleteDepartment($id);

            return $this->successResponse('Department deleted successfully!', []);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete department!', ['error' => $e->getMessage()], 500);
        }
    }

}
