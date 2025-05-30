<?php

namespace App\Http\Controllers\Api\V1\Ats;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Ats\JobSelectionOptionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobSelectionOptionsController extends Controller
{
    use ApiResponse;

    protected JobSelectionOptionService $service;

    public function __construct(JobSelectionOptionService $service)
    {
        $this->service = $service;
    }

    public function showByJobApplicationId(int $jobApplicationId): JsonResponse
    {
        try {
            $option = $this->service->getByJobApplicationId($jobApplicationId);
            return $this->successResponse('Job selection option retrieved successfully!', $option);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Job selection option not found.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve job selection option.', ['error' => $e->getMessage()], 500);
        }
    }
}
