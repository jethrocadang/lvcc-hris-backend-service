<?php

namespace App\Http\Controllers\Api\V1\Hris;

use Exception;
use App\Models\Employee;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Services\Hris\EmployeeService;
use App\Http\Resources\EmployeeResource;
use App\Http\Requests\EmployeeInformationRequest;

class EmployeeController extends Controller
{
    use ApiResponse;

    private EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        // Validate the request
        $filters = $request->all();
        $perPage = (int) ($filters['per_page'] ?? 10);

        $employees = $this->employeeService->getEmployees($filters, $perPage);

        // Pagination info
        $meta = [
            'current_page' => $employees->currentPage(),
            'last_page' => $employees->lastPage(),
            'total' => $employees->total(),
        ];

        // Return data with meta
        return $this->successResponse(
            'Employees retrieved successfully!',
            EmployeeResource::collection($employees),
            200,
            $meta
        );
    }

    public function getByAuthenticatedUser(): JsonResponse
    {
        try {
            $employee = $this->employeeService->getEmployeeByAuthenitcatedUser();
            return $this->successResponse('Employee retrieved successfully!', $employee);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve employee.', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeInformationRequest $infoRequest, EmployeeRequest $employeeRequest): JsonResponse
    {
        try {
            $employee = $this->employeeService->createEmployee($infoRequest, $employeeRequest);
            return $this->successResponse('Employee created successfully!', $employee, 201);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create employee.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $employee = $this->employeeService->getEmployeeById($id);
            return $this->successResponse('Employee retrieved successfully!', $employee);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve employee.', ['error' => $e->getMessage()], 404);
        }
    }

    // public function update(EmployeeInformationRequest $infoRequest, EmployeeRequest $employeeRequest, int $id): JsonResponse
    // {
    //     try {
    //         $employee = $this->employeeService->updateEmployee($infoRequest, $employeeRequest,$id);
    //         return $this->successResponse('Employee updated successfully!', $employee);                  <!-- updating employee and its info [not-working] -->
    //     } catch (Exception $e) {
    //         return $this->errorResponse('Failed to update employee.', ['error' => $e->getMessage()], 500);
    //     }
    // }

    /**
     * Update the Employee Information only.
     */
    public function updateInformationOnly(EmployeeInformationRequest $request, int $id): JsonResponse
    {
        try {
            $employee = $this->employeeService->updateEmployeeInformationOnly($request, $id);
            return $this->successResponse('Employee information updated successfully!', $employee);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update employee information.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the Employee only.
     */
    public function updateEmployeeOnly(EmployeeRequest $request, int $id): JsonResponse
    {
        try {
            $employee = $this->employeeService->updateEmployeeOnly($request, $id);
            return $this->successResponse('Employee updated successfully!', $employee);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update employee.', ['error' => $e->getMessage()], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->employeeService->deleteEmployee($id);
            return $this->successResponse('Employee deleted successfully!', []);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete employee.', ['error' => $e->getMessage()], 500);
        }
    }
}


//fix pati dapat employee_info deleted
