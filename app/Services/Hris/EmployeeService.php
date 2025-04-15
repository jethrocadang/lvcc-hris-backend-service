<?php

namespace App\Services\Hris;

use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\EmployeeInformationRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\EmployeeInformation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    public function getEmployees(){
        $employees = Employee::all();

        return $employees->isNotEmpty()
        ? EmployeeResource::collection($employees)->collection
        : collect();
    }

    public function getEmployeeById(int $id): EmployeeResource
    {
        $employee = Employee::findOrFail($id);

        return new EmployeeResource($employee);
    }

    public function createEmployee(EmployeeInformationRequest $infoRequest, EmployeeRequest $employeeRequest): EmployeeResource
    {
        try {
            // Step 1: Create EmployeeInformation
            $employeeInfo = EmployeeInformation::create($infoRequest->validated());

            // Step 2: Merge info ID into Employee data
            $employeeData = $employeeRequest->validated();
            $employeeData['employee_information_id'] = $employeeInfo->id;

            // Step 3: Create Employee
            $employee = Employee::create($employeeData);

            // Eager load the relation
            $employee->load(['employeeInformation', 'departmentJobPosition', 'user']);

            return new EmployeeResource($employee);
        } catch (Exception $e) {
            Log::error('Employee creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // public function updateEmployee(EmployeeInformationRequest $infoRequest, EmployeeRequest $employeeRequest, int $id): EmployeeResource
    // {
    //     try {
    //         // Step 1: Find employee
    //         $employee = Employee::findOrFail($id);

    //         // Step 2: Update employee information
    //         $employee->employeeInformation->update($infoRequest->validated());

    //         // Step 3: Update employee data                                           <!-- updating employee and its info [not-working] -->
    //         $employee->update($employeeRequest->validated());

    //         return new EmployeeResource($employee);
    //     } catch (Exception $e) {
    //         Log::error('Employee update failed', ['error' => $e->getMessage()]);
    //         throw $e;
    //     }
    // }

    public function updateEmployeeInformationOnly(EmployeeInformationRequest $request, int $id): EmployeeResource
    {
        try {
            // find employee
            $employee = Employee::findOrFail($id);
    
            //update  employee targeting employee information
            $employee->employeeInformation->update($request->validated());
    
            return new EmployeeResource($employee);
        } catch (Exception $e) {
            Log::error('Employee info-only update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateEmployeeOnly(EmployeeRequest $request, int $id): EmployeeResource
    {
        try {
            //find employee
            $employee = Employee::findOrFail($id);

            $employee->update($request->validated());

            return new EmployeeResource($employee);
        } catch (Exception $e) {
            Log::error('Employee-only update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteEmployee(int $id): bool
    {
        try {
            // Find the employee by ID
            $employee = Employee::findOrFail($id);

            // Delete the employee
            return $employee->delete();
        } catch (Exception $e) {
            Log::error('Employee deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    
}
