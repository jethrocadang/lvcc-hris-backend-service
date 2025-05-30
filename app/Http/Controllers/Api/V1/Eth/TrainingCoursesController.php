<?php

namespace App\Http\Controllers\Api\V1\Eth;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Eth\TrainingCourseFilterRequest;
use App\Services\Eth\TrainingCoursesService;

use App\Http\Requests\Eth\TrainingCoursesRequest;
use App\Http\Resources\Eth\TrainingCoursesResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class TrainingCoursesController extends Controller
{
    use ApiResponse;

    private TrainingCoursesService $trainingCoursesService;

    public function __construct(TrainingCoursesService $trainingCoursesService)
    {
        $this->trainingCoursesService = $trainingCoursesService;
    }

    /**
     * Display a listing of the resource.
     */
public function index(TrainingCourseFilterRequest $request): JsonResponse
{
    $filters = $request->validated();
    $perPage = (int) ($filters['per_page'] ?? 10);

    $trainingCourses = $this->trainingCoursesService->getTrainingCourses($filters, $perPage);

    $meta = [
        'current_page' => $trainingCourses->currentPage(),
        'last_page' => $trainingCourses->lastPage(),
        'total' => $trainingCourses->total(),
    ];

    return $this->successResponse(
        'Training courses retrieved successfully!',
        TrainingCoursesResource::collection($trainingCourses),
        200,
        $meta
    );
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainingCoursesRequest $request): JsonResponse
    {
        try{
            $trainingCourses = $this->trainingCoursesService->createTrainingCourse($request);
            return $this->successResponse('Training course created successfully!', $trainingCourses, 201);
        }catch (Exception $e){
            return $this->errorResponse('An error occured while creating the training course.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $trainingCourses = $this->trainingCoursesService->getTrainingCourseById($id);
            return $this->successResponse('Training courses retrieved successfully!', $trainingCourses);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve Training courses!', ['error' => $e->getMessage()], 500);
        }
    }

    public function update(TrainingCoursesRequest $request, int $id): JsonResponse
    {
        try{
            $trainingCourses = $this->trainingCoursesService->updateTrainingCourse($request, $id);
            return $this->successResponse('Training course updated successfully!', $trainingCourses);
        }catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        }catch (Exception $e) {
            return $this->errorResponse('Failed to update training course.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            // Find the email template by ID
            $this->trainingCoursesService->deleteTrainingCourse($id);

            // Return success response
            return $this->successResponse('Training course deleted successfully!', []);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete training course!', ['error' => $e->getMessage()], 500);
        }
    }
}
