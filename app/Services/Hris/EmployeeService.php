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
    public function createEmployee(EmployeeInformationRequest $infoRequest, EmployeeRequest $employeeRequest): EmployeeResource
    {
        DB::beginTransaction();

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

            DB::commit();
            return new EmployeeResource($employee);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Employee creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
