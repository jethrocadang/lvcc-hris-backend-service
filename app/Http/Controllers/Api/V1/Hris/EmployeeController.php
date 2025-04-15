<?php

namespace App\Http\Controllers\Api\V1\Hris;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeInformationRequest;
use App\Http\Requests\EmployeeRequest;
use App\Services\Hris\EmployeeService;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

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
    public function index(): JsonResponse
    {
        try {
            $employees = $this->employeeService->getEmployees();
            return $this->successResponse('Employees retrieved successfully!', $employees);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve employees.', ['error' => $e->getMessage()], 500);
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