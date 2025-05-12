<?php

namespace App\Services\Eth;

use App\Http\Requests\Eth\TrainingCourseFeedbackRequest;
use App\Http\Resources\Eth\TrainingCourseFeedbackResource;
use App\Models\TrainingCourseFeedback;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class TrainingCourseFeedbackService
{
    public function getCourseFeedbacks()
    {
        $courseFeedback = TrainingCourseFeedback::all();

        return $courseFeedback->isNotEmpty()
        ? TrainingCourseFeedbackResource::collection($courseFeedback)->collection
        : collect();
    }
    

    public function getCourseFeedbackById(int $id)
    {
        try{

            $courseFeedback = TrainingCourseFeedback::findOrFail($id);

            return new TrainingCourseFeedbackResource($courseFeedback);
        } catch(ModelNotFoundException $e) {
            Log::error('Feedback not found.',['error' => $e->getMessage()]);
            throw $e;
        } catch(Exception $e){
            Log::error('Feedback retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function createCourseFeedback(TrainingCourseFeedbackRequest $request): TrainingCourseFeedbackResource
    {
        try {
            $employee = auth('api')->user();
    
            $data = $request->validated();
            $data['employee_id'] = $employee->id; // set the logged-in user as the author
    
            $courseFeedback = TrainingCourseFeedback::create($data);

            return new TrainingCourseFeedbackResource($courseFeedback);
        } catch (Exception $e) {
            Log::error('Feedback creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateCourseFeedback(TrainingCourseFeedbackRequest $request, int $id): TrainingCourseFeedbackResource
    {
        try {
            $courseFeedback = TrainingCourseFeedback::findOrFail($id);
            $employee = auth('api')->user();
            $data = $request->validated();
            $data['employee_id'] = $employee->id; // set the logged-in user as the author
            $courseFeedback->update($data);

            return new TrainingCourseFeedbackResource($courseFeedback);
        } catch (ModelNotFoundException $e) {
            Log::error('Feedback not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Feedback update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}