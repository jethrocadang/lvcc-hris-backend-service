<?php

namespace App\Http\Controllers\Api\V1\Eth;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Eth\ExternalTrainingAttendanceRequest;
use App\Models\ExternalTrainingAttendance;
use App\Services\Eth\ExternalTrainingAttendanceService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExternalTrainingAttendanceController extends Controller
{
    use ApiResponse;

    private ExternalTrainingAttendanceService $externalTrainingAttendanceService;

    public function __construct(ExternalTrainingAttendanceService $externalTrainingAttendanceService)
    {
        $this->externalTrainingAttendanceService = $externalTrainingAttendanceService;
    }
    public function index(): JsonResponse
    {
        $externalTrainingAttendance = $this->externalTrainingAttendanceService->getExternalTrainingAttendances();

        return $externalTrainingAttendance->isNotEmpty()
            ? $this->successResponse('External training attendance retrieved successfully!', $externalTrainingAttendance)
            : $this->errorResponse('No external training attendance found', [], 404);
    }

    public function getByEmployeeId(int $employeeId): JsonResponse
    {
        try {
            $externalTrainingAttendances = $this->externalTrainingAttendanceService->getExternalTrainingAttendanceByEmployeeId($employeeId);

            return $externalTrainingAttendances->isNotEmpty()
                ? $this->successResponse('External training attendance for employee retrieved successfully!', $externalTrainingAttendances)
                : $this->errorResponse('No external training attendance found for this employee', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve external training attendance!', ['error' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $externalTrainingAttendance = $this->externalTrainingAttendanceService->getExternalTrainingAttendanceById($id);
            return $this->successResponse('External training attendance retrieved successfully!', $externalTrainingAttendance);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve external training attendance!', ['error' => $e->getMessage()], 500);
        }
    }
    public function store(ExternalTrainingAttendanceRequest $request): JsonResponse
    {
        try {
            $externalTrainingAttendance = $this->externalTrainingAttendanceService->createExternalTrainingAttendance($request);
            return $this->successResponse('External training attendance created successfully!', $externalTrainingAttendance, 201);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while creating the external training attendance.', ['error' => $e->getMessage()], 500);
        }
    }

    public function update(int $id, ExternalTrainingAttendanceRequest $request): JsonResponse
    {
        try {
            $externalTrainingAttendance = $this->externalTrainingAttendanceService->updateExternalTrainingAttendance($id, $request);
            return $this->successResponse('External training attendance updated successfully!', $externalTrainingAttendance);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update external training attendance!', ['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try{
            $this->externalTrainingAttendanceService->deleteExternalTrainingAttendance($id);
            return $this->successResponse('External training attendance deleted successfully!', []);
        }catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete external training attendance!', ['error' => $e->getMessage()], 500);
        }
    }
}

