<?php

namespace App\Services\Ats;

use Exception;
use App\Models\JobSelectionOption;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\JobSelectionOptionsResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobSelectionOptionService
{
    public function getByJobApplicationId(int $jobApplicationId): JobSelectionOptionsResource
    {
        try {
            $option = JobSelectionOption::with('jobPost')
                ->where('job_application_id', $jobApplicationId)
                ->firstOrFail();

            return new JobSelectionOptionsResource($option);
        } catch (ModelNotFoundException $e) {
            Log::warning('JobSelectionOption not found.', ['job_application_id' => $jobApplicationId]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Failed to retrieve JobSelectionOption.', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
