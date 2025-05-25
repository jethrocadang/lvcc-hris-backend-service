<?php

namespace App\Services\Ats;

use App\Http\Requests\Ats\UpdateJobApplicationPhaseTwoRequest;
use App\Http\Resources\JobApplicationProgressResource;
use App\Mail\JobApplicationEmail;
use App\Models\JobApplication;
use App\Models\JobApplicationProgress;
use Date;
use Illuminate\Support\Facades\Log;

use Exception;
use Mail;

class JobApplicationProgressService
{
    public function getJoApplicationProgressByUser(int $id)
    {
        try {
            // check authenticated job aplicant
            $jobApplication = JobApplication::findOrFail($id);

            // get job application progress of the authenticated job applicant
            $jobApplicationProgress = $jobApplication->jobApplicationProgress;

            return $jobApplicationProgress;
        } catch (Exception $e) {
            Log::error('Error fetching Job Application Progress!' . $e->getMessage());
            throw $e;
        }
    }

    public function getAllJobApplicationProgress() {}

    public function updatePhase(UpdateJobApplicationPhaseTwoRequest $request): array
    {
        try {
            // Get the authenticated evaluator or the admin reviewer
            $admin = auth('api')->user();

            // Validate the request [job_application_id, reviewer_remarks, status(accepted, pending, rejected)]
            $validated = $request->validated();
            $status = $validated['status'];
            $currentPhaseId = $validated['current_phase_id'];
            $nextPhaseId = $validated['next_phase_id'];
            $screeningType = $validated['screening_type'];

            Log::info("[Update Job Application Phase {$currentPhaseId}] Admin: ", ['id:' => $admin->id, 'name:' => $admin->name]);
            // Update the current phase either [accepted or rejected]
            $progress = $this->updateCurrentPhase($validated, $currentPhaseId, $admin);

            // Get the Job Application, then the Job Applicant for the email automation
            $jobApplication = $progress->jobApplication;
            $applicant = $jobApplication->jobApplicant;

            // This method is for sending emails
            $this->sendStatusEmail($progress, $applicant, $jobApplication->portal_token, $status);

            // If and only if the status is accepted, next phase is created
            if ($status === 'accepted' && $nextPhaseId) {
                $this->createNextPhase($jobApplication->id, $nextPhaseId, $screeningType );
            }

            // Update message
            $message = match ($status) {
                'accepted' => $nextPhaseId
                    ? "Job application accepted and moved to phase {$nextPhaseId}."
                    : "Job application accepted at phase {$currentPhaseId}.",
                'rejected' => "Job application was rejected at phase {$currentPhaseId}.",
                default => "Job application updated at phase {$currentPhaseId} with status: {$status}.",
            };

            // Returns array [resource & message]
            return [
                'resource' => new JobApplicationProgressResource($progress),
                'message' => $message,
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the current phase progress record
     */
    private function updateCurrentPhase(array $validated, int $phaseId, $admin): JobApplicationProgress
    {
        $progress = JobApplicationProgress::where('job_application_id', $validated['job_application_id'])
            ->where('job_application_phase_id', $phaseId)
            ->firstOrFail();

        $progress->update([
            'status' => $validated['status'],
            'reviewed_by' => $admin->id,
            'reviewer_remarks' => $validated['reviewer_remarks'] ?? null,
            'end_date' => now(),
        ]);

        return $progress;
    }

    /**
     * Send status email to applicant
     */
    private function sendStatusEmail(JobApplicationProgress $progress, $applicant, string $portalToken, string $status): void
    {
        Log::info('Status: '. $status);
        $phase = $progress->phase;
        $emailTemplate = $status === 'accepted'
            ? $phase->acceptanceTemplate
            : $phase->rejectionTemplate;

        Log::info('Email Template: '. $emailTemplate);
        if ($emailTemplate) {
            Mail::to($applicant->email)->send(
                new JobApplicationEmail($applicant, $emailTemplate, $portalToken)
            );
        }
    }

    /**
     * Create the next phase progress record
     */
    private function createNextPhase(int $jobApplicationId, int $nextPhaseId, string $screeningType): void
    {
        JobApplicationProgress::create([
            'job_application_id' => $jobApplicationId,
            'job_application_phase_id' => $nextPhaseId,
            'screening_type' => $screeningType,
            'status' => 'in-progress',
            'start_date' => now()
        ]);
    }
}
