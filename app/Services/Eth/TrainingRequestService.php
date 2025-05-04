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
    public function getTrainingRequest(){
        
        $trainingRequest = TrainingRequest::all();

        return $trainingRequest->isNotEmpty()
        ? trainingRequestResource::collection($trainingRequest)->collection
        : collect();
    }
}