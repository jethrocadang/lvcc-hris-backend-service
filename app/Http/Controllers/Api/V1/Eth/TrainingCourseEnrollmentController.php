<?php

namespace App\Http\Controllers\Api\V1\Eth;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Eth\TrainingCourseEnrollmentRequest;
use App\Models\TrainingCourseEnrollment;
use App\Services\Eth\TrainingCourseEnrollmentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TrainingCourseEnrollmentController extends Controller
{
    use ApiResponse;

    private TrainingCourseEnrollmentService $courseEnrollmentService;

    public function __construct(TrainingCourseEnrollmentService $courseEnrollmentService)
    {
        $this->courseEnrollmentService = $courseEnrollmentService;
    }

    public function index(): JsonResponse
    {
        $courseEnrollment = $this->courseEnrollmentService->getCourseEnrollments();

        return $courseEnrollment->isNotEmpty()
            ? $this->successResponse('Enrollments retrieved successfully!', $courseEnrollment)
            : $this->errorResponse('No enrollments found', [], 404);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $courseEnrollment = $this->courseEnrollmentService->getCourseEnrollmentById($id);
            return $this->successResponse('Enrollment retrieved successfully!', $courseEnrollment);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve enrollment!', ['error' => $e->getMessage()], 500);
        }
    }

    public function store(TrainingCourseEnrollmentRequest $request): JsonResponse
    {
        try {
            $courseEnrollment = $this->courseEnrollmentService->createCourseEnrollment($request);
            return $this->successResponse('Enrollment created successfully!', $courseEnrollment, 201);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while creating the enrollment.', ['error' => $e->getMessage()], 500);
        }
    }

    public function cancel(int $id): JsonResponse
    {
        try {
            $courseEnrollment = $this->courseEnrollmentService->cancelCourseEnrollment($id);
            return $this->successResponse('Enrollment cancelled successfully!', $courseEnrollment);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to cancel enrollment.', ['error' => $e->getMessage()], 500);
        }
    }
    
}
