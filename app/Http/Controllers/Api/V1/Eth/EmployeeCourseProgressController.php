<?php

namespace App\Http\Controllers\Api\V1\Eth;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Eth\EmployeeCourseProgressRequest;
use App\Models\EmployeeCourseProgress;
use App\Services\Eth\EmployeeCourseProgressService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeCourseProgressController extends Controller
{
    use ApiResponse;

    private EmployeeCourseProgressService $employeeCourseProgressService;

    public function __construct(EmployeeCourseProgressService $employeeCourseProgressService)
    {
        $this->employeeCourseProgressService = $employeeCourseProgressService;
    }

    public function index(): JsonResponse
    {
        $employeeCourseProgress = $this->employeeCourseProgressService->getEmployeeCourseProgress();

        return $employeeCourseProgress->isNotEmpty()
            ? $this->successResponse('Employee course progress retrieved successfully!', $employeeCourseProgress)
            : $this->errorResponse('No employee course progress found', [], 404);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $employeeCourseProgress = $this->employeeCourseProgressService->getEmployeeCourseProgressById($id);
            return $this->successResponse('Employee course progress retrieved successfully!', $employeeCourseProgress);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve employee course progress!', ['error' => $e->getMessage()], 500);
        }
    }

    public function store(EmployeeCourseProgressRequest $request): JsonResponse
    {
        try {
            $employeeCourseProgress = $this->employeeCourseProgressService->createEmployeeCourseProgress($request);
            return $this->successResponse('Employee course progress created successfully!', $employeeCourseProgress, 201);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while creating the employee course progress.', ['error' => $e->getMessage()], 500);
        }
    }

    // public function update(int $id, EmployeeCourseProgressRequest $request): JsonResponse
    // {
    //     try {
    //         $employeeCourseProgress = $this->employeeCourseProgressService->updateEmployeeCourseProgress($id, $request);
    //         return $this->successResponse('Employee course progress updated successfully!', $employeeCourseProgress);
    //     } catch (ModelNotFoundException $e) {
    //         return $this->errorResponse($e->getMessage(), [], 404);
    //     } catch (Exception $e) {
    //         return $this->errorResponse('Failed to update employee course progress!', ['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function updateLastPosition($id) 
    // {
    //     return $this->employeeCourseProgressService->updateLastPosition($id);
    // }
}
