<?php

namespace App\Services\Ats;

use Exception;
use Illuminate\Support\Facades\Log;

class JobApplicationProgressService
{
    public function getJoApplicationProgressByUser()
    {
        try {
            // check authenticated job aplicant
            $jobApplication = auth('ats')->user();

            if (!$jobApplication) {
                throw new Exception('Unauthorized', 401);
            }

            // get job application progress of the authenticated job applicant
            $jobApplicationProgress = $jobApplication->jobApplicationProgress;

            return $jobApplicationProgress;
        } catch (Exception $e) {
            Log::error('Error fetching Job Application Progress!' . $e->getMessage());
            throw $e;
        }
    }

    public function getAllJobApplicationProgress()
    {
        
    }
}
