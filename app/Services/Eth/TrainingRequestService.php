<?php

namespace App\Services\Eth;

use Exception;
use App\Models\TrainingRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Eth\TrainingRequestRequest;
use App\Http\Resources\Eth\TrainingRequestResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TrainingRequestService
{
    public function getTrainingRequest(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        try{
            return QueryBuilder::for(TrainingRequest::with([
                'employee',
                'supervisor',
                'officer'
            ]))
                ->allowedFilters([
                    AllowedFilter::exact('employee_id'),
                    AllowedFilter::exact('supervisor_status'),
                    AllowedFilter::exact('officer_status'),
                ])
                ->allowedSorts(['request_status','created_at'])
                ->paginate($perPage)
                ->appends($filters);
        }catch (Exception $e) {
            Log::error('Failed to retrieve interview schedules', ['error' => $e->getMessage()]);
            return new LengthAwarePaginator([], 0, $perPage);
        }
    }

    public function getTrainingRequestById(int $id)
    {
        try{

            $trainingRequest = TrainingRequest::findOrFail($id);

            return new TrainingRequestResource($trainingRequest);
        } catch(ModelNotFoundException $e) {
            Log::error('Training request not found.',['error' => $e->getMessage()]);
            throw $e;
        } catch(Exception $e){
            Log::error('Training request retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function createTrainingRequest(TrainingRequestRequest $request): TrainingRequestResource
    {
        try {

            $employee = auth('api')->user();

            $data = $request->validated();
            $data['employee_id'] = $employee->id;

            $trainingRequest = TrainingRequest::create($data);

            return new TrainingRequestResource($trainingRequest);
        } catch (Exception $e) {
            Log::error('Training request creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function approveBySupervisor(int $id): TrainingRequestResource
    {
        try {
            $trainingRequest = TrainingRequest::findOrFail($id);

            $supervisor = auth('api')->user();

            $trainingRequest->update([
                'supervisor_id' => $supervisor->id,
                'supervisor_status' => 'approved',
                'supervisor_reviewed_at' => now(),
                'request_status' => 'pending', // still pending, waiting for officer
            ]);

            return new TrainingRequestResource($trainingRequest);
        } catch (ModelNotFoundException $e) {
            Log::error("Training request not found.", ['id' => $id]);
            throw $e;
        } catch (Exception $e) {
            Log::error("Supervisor approval failed", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function approveByOfficer(int $id): TrainingRequestResource
    {
        try {
            $trainingRequest = TrainingRequest::findOrFail($id);

            $officer = auth('api')->user(); // final approver

            $trainingRequest->update([
                'officer_id' => $officer->id,
                'officer_status' => 'approved',
                'officer_reviewed_at' => now(),
                'request_status' => 'approved', // final approval
            ]);

            return new TrainingRequestResource($trainingRequest);
        } catch (ModelNotFoundException $e) {
            Log::error("Training request not found.", ['id' => $id]);
            throw $e;
        } catch (Exception $e) {
            Log::error("Officer approval failed", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

        public function rejectBySupervisor(int $id): TrainingRequestResource
    {
        try {
            $trainingRequest = TrainingRequest::findOrFail($id);

            $supervisor = auth('api')->user();

            $trainingRequest->update([
                'supervisor_id' => $supervisor->id,
                'supervisor_status' => 'rejected',
                'supervisor_reviewed_at' => now(),
                'request_status' => 'rejected', // still pending, waiting for officer
            ]);

            return new TrainingRequestResource($trainingRequest);
        } catch (ModelNotFoundException $e) {
            Log::error("Training request not found.", ['id' => $id]);
            throw $e;
        } catch (Exception $e) {
            Log::error("Supervisor approval failed", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function rejectByOfficer(int $id): TrainingRequestResource
    {
        try {
            $trainingRequest = TrainingRequest::findOrFail($id);

            $officer = auth('api')->user(); // final approver

            $trainingRequest->update([
                'officer_id' => $officer->id,
                'officer_status' => 'rejected',
                'officer_reviewed_at' => now(),
                'request_status' => 'rejected', // final approval
            ]);

            return new TrainingRequestResource($trainingRequest);
        } catch (ModelNotFoundException $e) {
            Log::error("Training request not found.", ['id' => $id]);
            throw $e;
        } catch (Exception $e) {
            Log::error("Officer approval failed", ['error' => $e->getMessage()]);
            throw $e;
        }
    }


}
