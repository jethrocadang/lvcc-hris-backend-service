<?php

namespace App\Http\Controllers\Api\V1\Eth;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Eth\TrainingCourseModuleRequest;
use App\Models\TrainingCourseModule;
use App\Services\Eth\TrainingCourseModuleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TrainingCourseModuleController extends Controller
{
    use ApiResponse;

    private TrainingCourseModuleService $courseModuleService;

    public function __construct(TrainingCourseModuleService $courseModuleService)
    {
        $this->courseModuleService = $courseModuleService;
    }

    public function index(): JsonResponse
    {
        $courseModule = $this->courseModuleService->getCourseModules();

        return $courseModule->isNotEmpty()
            ? $this->successResponse('Modules retrieved successfully!', $courseModule)
            : $this->errorResponse('No modules found', [], 404);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $courseModule = $this->courseModuleService->getCourseModuleById($id);
            return $this->successResponse('Module retrieved successfully!', $courseModule);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Module not found!', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve module!', ['error' => $e->getMessage()], 500);
        }
    }

    public function store(TrainingCourseModuleRequest $request): JsonResponse
    {
        try {
            $courseModule = $this->courseModuleService->createCourseModule($request);
            return $this->successResponse('Module created successfully!', $courseModule, 201);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while creating the module.', ['error' => $e->getMessage()], 500);
        }
    }

    public function update(TrainingCourseModuleRequest $request, int $id): JsonResponse
    {
        try {
            $courseModule = $this->courseModuleService->updateCourseModule($request, $id);
            return $this->successResponse('Module updated successfully!', $courseModule);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Module not found!', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update module!', ['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $courseModule = $this->courseModuleService->deleteCourseModule($id);
            return $this->successResponse('Module deleted successfully!', $courseModule);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete module!', ['error' => $e->getMessage()], 500);
        }
    }

}
