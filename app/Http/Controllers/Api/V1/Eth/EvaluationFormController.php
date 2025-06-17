<?php

namespace App\Http\Controllers\Api\V1\Eth;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Eth\EvaluationFormRequest;
use App\Http\Requests\Eth\EvaluationFormFilterRequest;
use App\Http\Requests\Eth\EvaluationResponseRequest;
use App\Http\Resources\Eth\EvaluationFormResource;
use App\Services\Eth\EvaluationFormService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EvaluationFormController extends Controller
{
    use ApiResponse;

    private EvaluationFormService $evaluationFormService;

    public function __construct(EvaluationFormService $evaluationFormService)
    {
        $this->evaluationFormService = $evaluationFormService;
    }

    /**
     * Display a listing of evaluation forms.
     */
    public function index(EvaluationFormFilterRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 10);

        $evaluationForms = $this->evaluationFormService->getEvaluationForms($filters, $perPage);

        $meta = [
            'current_page' => $evaluationForms->currentPage(),
            'last_page' => $evaluationForms->lastPage(),
            'total' => $evaluationForms->total(),
        ];

        return $this->successResponse(
            'Evaluation forms retrieved successfully!',
            EvaluationFormResource::collection($evaluationForms),
            200,
            $meta
        );
    }

    /**
     * Store a newly created evaluation form.
     */
    public function store(EvaluationFormRequest $request): JsonResponse
    {
        try {
            $evaluationForm = $this->evaluationFormService->createEvaluationForm($request);
            return $this->successResponse('Evaluation form created successfully!', $evaluationForm, 201);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while creating the evaluation form.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified evaluation form.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $evaluationForm = $this->evaluationFormService->getEvaluationFormById($id);
            return $this->successResponse('Evaluation form retrieved successfully!', $evaluationForm);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Evaluation form not found.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve evaluation form!', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified evaluation form.
     */
    public function update(EvaluationFormRequest $request, string $id): JsonResponse
    {
        try {
            $evaluationForm = $this->evaluationFormService->updateEvaluationForm($request, $id);
            return $this->successResponse('Evaluation form updated successfully!', $evaluationForm);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Evaluation form not found.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update evaluation form.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified evaluation form.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->evaluationFormService->deleteEvaluationForm($id);
            return $this->successResponse('Evaluation form deleted successfully!', []);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Evaluation form not found.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete evaluation form!', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Submit responses for an evaluation form.
     */
    public function submitResponses(EvaluationResponseRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $employeeId = auth('api')->id();

            $result = $this->evaluationFormService->submitEvaluationResponses($data, $employeeId);

            return $this->successResponse('Evaluation responses submitted successfully!', []);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to submit evaluation responses.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all responses for a specific evaluation form.
     */
    public function getResponses(Request $request, string $formId): JsonResponse
    {
        try {
            $filters = $request->all();
            $perPage = (int) ($filters['per_page'] ?? 10);

            $responses = $this->evaluationFormService->getEvaluationResponses($formId, $filters, $perPage);

            $meta = [
                'current_page' => $responses->currentPage(),
                'last_page' => $responses->lastPage(),
                'total' => $responses->total(),
            ];

            return $this->successResponse(
                'Evaluation responses retrieved successfully!',
                $responses,
                200,
                $meta
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Evaluation form not found.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve evaluation responses.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get responses for a specific employee for a specific form.
     */
    public function getEmployeeResponses(string $formId, string $employeeId): JsonResponse
    {
        try {
            $responses = $this->evaluationFormService->getEmployeeEvaluationResponses($formId, $employeeId);

            return $this->successResponse(
                'Employee evaluation responses retrieved successfully!',
                $responses
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Evaluation form or employee not found.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve employee evaluation responses.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get evaluation forms by training course ID.
     */
    public function getByCourseId(string $courseId): JsonResponse
    {
        try {
            $evaluationForms = $this->evaluationFormService->getEvaluationFormByCourseId($courseId);
            return $this->successResponse('Evaluation forms for course retrieved successfully!', $evaluationForms);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('No evaluation forms found for this course.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve evaluation forms for course!', ['error' => $e->getMessage()], 500);
        }
    }
}
