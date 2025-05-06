<?php

namespace App\Services\Eth;

use App\Http\Requests\Eth\TrainingCoursesRequest;
use App\Http\Resources\Eth\TrainingCoursesResource;
use App\Models\TrainingCourse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TrainingCoursesService
{
    public function getTrainingCourses()
    {
        $trainingCourses = TrainingCourse::all();

        return $trainingCourses->isNotEmpty()
        ? TrainingCoursesResource::collection($trainingCourses)->collection
        : collect();
    }

    public function getTrainingCourseById(int $id)
    {
        try{

            $trainingCourses = TrainingCourse::findOrFail($id);

            return new TrainingCoursesResource($trainingCourses);
        } catch(ModelNotFoundException $e) {
            Log::error('Training course not found.',['error' => $e->getMessage()]);
            throw $e;
        } catch(Exception $e){
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
        try{
            $trainingCourse = TrainingCourse::findOrFail($id);
            return $trainingCourse->delete();
        }catch  (Exception $e){
            Log::error('Training course deletion failed.', ['error' => $e->getMessage()]);
            return false;
        }
    }


    
}