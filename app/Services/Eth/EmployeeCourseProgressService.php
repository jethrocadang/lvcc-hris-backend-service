<?php

namespace App\Services\Eth;

use App\Http\Requests\Eth\EmployeeCourseProgressRequest;
use App\Http\Resources\Eth\EmployeeCourseProgressResource;
use App\Models\EmployeeCourseProgress;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class EmployeeCourseProgressService
{
    public function getEmployeeCourseProgress()
    {
        $employeeCourseProgress = EmployeeCourseProgress::all();

        return $employeeCourseProgress->isNotEmpty()
        ? EmployeeCourseProgressResource::collection($employeeCourseProgress)->collection
        : collect();
    }
    public function getEmployeeCourseProgressById(int $id)
    {
        try{
            $employeeCourseProgress = EmployeeCourseProgress::findOrFail($id);

            return new EmployeeCourseProgressResource($employeeCourseProgress);
        } catch(ModelNotFoundException $e) {
            Log::error('Employee course progress not found.',['error' => $e->getMessage()]);
            throw $e;
        } catch(Exception $e){
            Log::error('Employee course progress retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function createEmployeeCourseProgress(EmployeeCourseProgressRequest $request): EmployeeCourseProgressResource
    {
        try {
            $employee = auth('api')->user();

            $data = $request->validated();
            $data['employee_id'] = $employee->id; // set the logged-in user as the author
            // $data['watched_seconds'] = 0; // set to 0
            // $data['last_position'] = 0; // set to 0
            // $data['completion_date'] = null; // set to null

            $employeeCourseProgress = EmployeeCourseProgress::create($data);

            return new EmployeeCourseProgressResource($employeeCourseProgress);
        } catch (Exception $e) {
            Log::error('Employee course progress creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    // public function updateEmployeeCourseProgress(int $id, EmployeeCourseProgressRequest $request): EmployeeCourseProgressResource
    // {
    //     try {
    //         $employeeCourseProgress = EmployeeCourseProgress::findOrFail($id);

    //         $data = $request->validated();
    //         $employeeCourseProgress->update($data);

    //         return new EmployeeCourseProgressResource($employeeCourseProgress);
    //     } catch (ModelNotFoundException $e) {
    //         Log::error('Employee course progress not found.',['error' => $e->getMessage()]);
    //         throw $e;
    //     } catch (Exception $e) {
    //         Log::error('Employee course progress update failed', ['error' => $e->getMessage()]);
    //         throw $e;
    //     }
    // }

    // public function updateLastPosition($id)
    // {
    //     try {
    //         $employeeCourseProgress = EmployeeCourseProgress::findOrFail($id);

    //         $employeeCourseProgress->last_position = request('last_position');
    //         $employeeCourseProgress->save();

    //         return new EmployeeCourseProgressResource($employeeCourseProgress);
    //     } catch (ModelNotFoundException $e) {
    //         Log::error('Employee course progress not found.',['error' => $e->getMessage()]);
    //         throw $e;
    //     } catch (Exception $e) {
    //         Log::error('Employee course progress update failed', ['error' => $e->getMessage()]);
    //         throw $e;
    //     }
    // }
}