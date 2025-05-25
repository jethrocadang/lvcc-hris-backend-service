<?php

namespace App\Services\Eth;

use App\Http\Requests\Eth\TrainingCoursesRequest;
use App\Http\Resources\Eth\TrainingCoursesResource;
use App\Models\TrainingCourse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Exception;

class TrainingCoursesService
{
    public function getTrainingCourses(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        try {
            return QueryBuilder::for(TrainingCourse::class)
                ->allowedFilters([
                    AllowedFilter::partial('title'),
                    AllowedFilter::exact('type'),
                ])
                ->allowedSorts(['created_at', 'title'])
                ->paginate($perPage)
                ->appends($filters);
        } catch (Exception $e) {
            Log::error('Failed to retrieve training courses', ['error' => $e->getMessage()]);
            return new LengthAwarePaginator([], 0, $perPage);
        }
    }

    public function getTrainingCourseById(int $id)
    {
        try {

            $trainingCourses = TrainingCourse::findOrFail($id);

            return new TrainingCoursesResource($trainingCourses);
        } catch (ModelNotFoundException $e) {
            Log::error('Training course not found.', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Training course retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function createTrainingCourse(TrainingCoursesRequest $request): TrainingCoursesResource
    {
        try {
            $author = auth('api')->user();

            $data = $request->validated();
            $data['author_id'] = $author->id; // set the logged-in user as the author

            $trainingCourse = TrainingCourse::create($data);

            return new TrainingCoursesResource($trainingCourse);
        } catch (Exception $e) {
            Log::error('Training course creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteTrainingCourse(int $id): bool
    {
        try {
            $trainingCourse = TrainingCourse::findOrFail($id);
            return $trainingCourse->delete();
        } catch (Exception $e) {
            Log::error('Training course deletion failed.', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
