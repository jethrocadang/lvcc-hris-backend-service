<?php

namespace App\Services\Ats;

// Models
use App\Models\JobApplicant;
use App\Models\JobApplicationProgress;

// Mails
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Mail\PortalAccessEmail;

// Helpers
use App\Http\Requests\Ats\JobApplicationCreateRequest;
use App\Http\Resources\JobApplicantResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\DB;

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

            // Begin transaction with the database
            DB::beginTransaction();
            Log::info('Database transaction started');

            // Mark the email as verified and clear the token
            $jobApplicant->update([
                'email_verified_at' => now(),
                'verification_token' => null,
            ]);
            Log::info('Email verified and token cleared', ['applicant_id' => $jobApplicant->id]);

            // If no portal access record exists, create one
            if (!$jobApplicant->jobApplication) {
                Log::info('No existing portal access found. Creating new portal access.', ['applicant_id' => $jobApplicant->id]);

                // Generate a secure random portal access token
                $portalToken = Str::random(40);

                // Create the job application record linked to this applicant
                $jobApplication = $jobApplicant->jobApplication()->create([
                    'portal_token' => $portalToken,
                ]);
                Log::info('Job application created', ['job_application_id' => $jobApplication->id]);

                // Start job application progress - Phase 1 completed
                JobApplicationProgress::create([
                    'job_application_id' => $jobApplication->id,
                    'job_application_phase_id' => 1,
                    'status' => 'accepted',
                    'start_date' => now(),
                    'end_date' => now(),
                ]);
                Log::info('Job application progress phase 1 completed', ['job_application_id' => $jobApplication->id]);

                // Start job application progress - Phase 2 pending
                JobApplicationProgress::create([
                    'job_application_id' => $jobApplication->id,
                    'job_application_phase_id' => 2,
                    'status' => 'accepted',
                    'start_date' => now(),
                ]);
                Log::info('Job application progress phase 2 started', ['job_application_id' => $jobApplication->id]);

                // If job_id is passed, create initial job selection option
                if ($jobId) {
                    $jobApplication->jobSelectionOptions()->create([
                        'job_id' => $jobId,
                        'priority' => 1,
                        'status' => null,
                    ]);
                    Log::info('Job selection option created', ['job_id' => $jobId]);
                }

                // Send portal access email to applicant
                Mail::to($jobApplicant->email)->send(new PortalAccessEmail($jobApplicant, $portalToken));
                Log::info('Portal access email sent', ['email' => $jobApplicant->email]);
            }

            // Commit transaction if everything is successful
            DB::commit();
            Log::info('Database transaction committed successfully');

            // Eager load the job application relationship for resource response
            $jobApplicant->load(['jobApplication']);

            // Return the applicant resource with portal token if created
            return new JobApplicantResource($jobApplicant);

        } catch (ModelNotFoundException $e) {
            Log::warning('Invalid verification token used', ['token' => $token]);
            throw new ModelNotFoundException('Token not found!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::warning('Database transaction rolled back due to an error');
            Log::error('Email verification process failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

}
