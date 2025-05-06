<?php

namespace App\Services\Eth;

use App\Http\Requests\Eth\TrainingCourseEnrollmentRequest;
use App\Http\Resources\Eth\TrainingCourseEnrollmentResource;
use App\Models\TrainingCourseEnrollment;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class TrainingCourseEnrollmentService
{
    public function getCourseEnrollments()
    {
        $courseEnrollment = TrainingCourseEnrollment::all();

        return $courseEnrollment->isNotEmpty()
        ? TrainingCourseEnrollmentResource::collection($courseEnrollment)->collection
        : collect();
    }

    public function getCourseEnrollmentById(int $id)
    {
        try{

            $courseEnrollment = TrainingCourseEnrollment::findOrFail($id);

            return new TrainingCourseEnrollmentResource($courseEnrollment);
        } catch(ModelNotFoundException $e) {
            Log::error('Enrollment not found.',['error' => $e->getMessage()]);
            throw $e;
        } catch(Exception $e){
            Log::error('Enrollment retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function createCourseEnrollment(TrainingCourseEnrollmentRequest $request): TrainingCourseEnrollmentResource
    {
        try {
            $employee = auth('api')->user();
    
            $data = $request->validated();
            $data['employee_id'] = $employee->id; // set the logged-in user as the author
            $data['enrollment_date'] = now(); // set automatically to current date
            $data['status'] = 'active'; // set to active
    
            $courseEnrollment = TrainingCourseEnrollment::create($data);

            return new TrainingCourseEnrollmentResource($courseEnrollment);
        } catch (Exception $e) {
            Log::error('Enrollment creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function cancelCourseEnrollment(int $id): TrainingCourseEnrollmentResource
    {
        try {
            $courseEnrollment = TrainingCourseEnrollment::findOrFail($id);
    
            //check if the authenticated user owns this enrollment to avoid cancellation by others
            $employee = auth('api')->user();
            if ($courseEnrollment->employee_id !== $employee->id) {
                throw new \Exception('Unauthorized to cancel this enrollment.');
            }
    
            $courseEnrollment->status = 'cancelled';
            $courseEnrollment->save();
    
            return new TrainingCourseEnrollmentResource($courseEnrollment);
        } catch (ModelNotFoundException $e) {
            Log::error('Enrollment not found.', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Enrollment cancellation failed.', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}