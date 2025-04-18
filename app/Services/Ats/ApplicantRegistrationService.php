<?php

namespace App\Services\Ats;

use App\Http\Requests\Ats\JobApplicationCreateRequest;
use App\Http\Resources\JobApplicantResource;
use App\Http\Resources\JobApplicationResource;
use App\Models\JobApplicant;
use App\Mail\VerificationEmail;
use App\Mail\PortalAccessEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApplicantRegistrationService
{
    /**
     * Handle creation of a new job applicant.
     * Sends a verification email after creating the applicant record.
     *
     * @param JobApplicationCreateRequest $request
     * @return JobApplicantResource
     * @throws Exception
     */
    public function create(JobApplicationCreateRequest $request): JobApplicantResource
    {
        try {
            // Validate the incoming request data
            $data = $request->validated();

            // Generate a secure random token for email verification
            $data['verification_token'] = Str::random(40);

            // Create a new job applicant record
            $jobApplicant = JobApplicant::create($data);

            //selected job
            $jobId = $data['job_id'];

            // Send verification email to applicant
            Mail::to($jobApplicant->email)->send(new VerificationEmail($jobApplicant, $jobId));

            // Return a resource representation of the job applicant
            return new JobApplicantResource($jobApplicant);
        } catch (Exception $e) {
            // Log the error and rethrow for handling by the controller
            Log::error('Job application creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Verify the email using a verification token.
     * After verification, generate and send a portal access token.
     *
     * @param string $token
     * @return JobApplicantResource
     * @throws ModelNotFoundException|Exception
     */
    public function verifyEmail(string $token, ?int $jobId = null): JobApplicantResource
    {
        try {
            // Find applicant using the verification token
            $jobApplicant = JobApplicant::where('verification_token', $token)->firstOrFail();

            // Mark the email as verified and clear the token
            $jobApplicant->update([
                'email_verified_at' => now(),
                'verification_token' => null,
            ]);

            Log::info("Job ID {$jobId}");

            // If no portal access record exists, create one and send portal token
            if (!$jobApplicant->jobApplication) {
                // Generate a secure random portal access token
                $portalToken = Str::random(40);

                // Create the job application record linked to this applicant
                $jobApplication = $jobApplicant->jobApplication()->create([
                    'portal_token' => $portalToken,
                ]);

                // If job_id is passed, create initial selection
                if ($jobId) {
                    $jobApplication->jobSelectionOptions()->create([
                        'job_id' => $jobId,
                        'priority' => 1,
                        'status' => null,
                    ]);
                }

                // Send portal access email to applicant
                Mail::to($jobApplicant->email)->send(new PortalAccessEmail($jobApplicant, $portalToken));

                Log::info("Portal access email queued for {$jobApplicant->email}");
            }

            // Eager load the job application for resource response
            $jobApplicant->load(['jobApplication']);

            // Return applicant resource including the portal access token
            return new JobApplicantResource($jobApplicant);
        } catch (ModelNotFoundException $e) {
            // Log and rethrow specific error if token is not found
            Log::warning("Invalid verification token used: {$token}");
            throw new ModelNotFoundException('Token not found!');
        } catch (Exception $e) {
            // Log any other unexpected errors
            Log::error('Email verification process failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
