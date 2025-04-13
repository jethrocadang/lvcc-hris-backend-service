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
    public function index()
    {
        
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
