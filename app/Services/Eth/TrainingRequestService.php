<?php

namespace App\Services\Eth;

use App\Http\Requests\Eth\TrainingRequestRequest;
use App\Http\Resources\Eth\TrainingRequestResource;
use App\Models\TrainingRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TrainingRequestService
{
    public function getTrainingRequest()
    {    
        $trainingRequest = TrainingRequest::all();

        return $trainingRequest->isNotEmpty()
        ? trainingRequestResource::collection($trainingRequest)->collection
        : collect();
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
            Log::error('Training retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function createTrainingRequest(TrainingRequestRequest $request): TrainingRequestResource
    {
        try {

            $employee = auth('api')->user();

            $trainingRequest = TrainingRequest::create($request->validated());

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
}