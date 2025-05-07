<?php

namespace App\Http\Controllers\Api\V1\Eth;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Eth\TrainingCourseFeedbackRequest;
use App\Models\TrainingCourseFeedback;
use App\Services\Eth\TrainingCourseFeedbackService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TrainingCourseFeedbackController extends Controller
{
    use ApiResponse;
    private TrainingCourseFeedbackService $courseFeedbackService;
    
    public function __construct(TrainingCourseFeedbackService $courseFeedbackService)
    {
        $this->courseFeedbackService = $courseFeedbackService;
    }

    public function index(): JsonResponse
    {
        $courseFeedback = $this->courseFeedbackService->getCourseFeedbacks();

        return $courseFeedback->isNotEmpty()
            ? $this->successResponse('Feedback retrieved successfully!', $courseFeedback)
            : $this->errorResponse('No feedback found', [], 404);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $courseFeedback = $this->courseFeedbackService->getCourseFeedbackById($id);
            return $this->successResponse('Feedback retrieved successfully!', $courseFeedback);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve feedback!', ['error' => $e->getMessage()], 500);
        }
    }
    
    public function store(TrainingCourseFeedbackRequest $request): JsonResponse
    {
        try {
            $courseFeedback = $this->courseFeedbackService->createCourseFeedback($request);
            return $this->successResponse('Feedback created successfully!', $courseFeedback, 201);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while creating the feedback.', ['error' => $e->getMessage()], 500);
        }
    }

    public function update(TrainingCourseFeedbackRequest $request, int $id): JsonResponse
    {
        try {
            $courseFeedback = $this->courseFeedbackService->updateCourseFeedback($request, $id);
            return $this->successResponse('Feedback updated successfully!', $courseFeedback);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update feedback!', ['error' => $e->getMessage()], 500);
        }
    }
}
