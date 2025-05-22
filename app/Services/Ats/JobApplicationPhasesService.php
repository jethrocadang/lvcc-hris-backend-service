<?php

namespace App\Services\Ats;

use App\Models\JobApplicationPhase;
use App\Http\Resources\JobApplicationPhaseResource;
use Illuminate\Support\Facades\DB;
use Exception;

class JobApplicationPhasesService
{
    /**
     * Get all job application phases ordered by sequence.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getJobApplicationPhases()
    {
        try {
            $phases = JobApplicationPhase::orderBy('sequence_order')->get();
            return JobApplicationPhaseResource::collection($phases);
        } catch (Exception $e) {
            throw new Exception("Failed to retrieve job application phases. " . $e->getMessage(), 500);
        }
    }

    /**
     * Update a job application phase.
     *
     * @param int $id
     * @param array $data
     * @return JobApplicationPhaseResource
     */
    public function updateJobApplicationPhase(int $id, array $data)
    {
        try {
            $phase = JobApplicationPhase::findOrFail($id);
            $phase->update($data);

            return new JobApplicationPhaseResource($phase);
        } catch (Exception $e) {
            throw new Exception("Failed to update job application phase. " . $e->getMessage(), 500);
        }
    }
}
