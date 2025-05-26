<?php

namespace App\Services\Ats;

use App\Http\Requests\JobApplicantInformationRequest;
use App\Http\Resources\JobApplicantResource;
use App\Http\Resources\JobApplicationWithInfoResource;
use App\Models\JobApplication;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class JobApplicationFormService
{
    public function update(JobApplicantInformationRequest $request)
    {
        try {
            $validated = $request->validated();
            /**
             * * Must be autenticated job applicant to access their own portal
             * * The portal_token from job_applications acts as a credentials for the Job Applicant
             * * The auth_guard is for ats and located in JobApplication model:
             * ? Curious check auth.config :)
             *  @var \App\Models\JobApplication $jobApplication */
            $jobApplication = auth('ats')->user();

            // Find the Job Applicant by Job Application
            $jobApplicant = $jobApplication->jobApplicant;

            // First they can update the data in Job Applicant
            $jobApplicant->fill(Arr::only($validated, [
                'first_name',
                'middle_name',
                'last_name',
                'email',
            ]))->save();

            // To access the JobApplicationInformation you need hasOneThrough, you can check the JobApplication Model
            $jobApplicantInfo = $jobApplication->jobApplicantInformation ?? $jobApplicant->jobApplicantInformation()->make();

            // Update or create ( Technically create ) Job Applicant Information
            $jobApplicantInfo->fill(Arr::only($validated, [
                'contact_number',
                'current_address',
                'religion',
                'locale_and_division',
                'servant_name',
                'servant_contact_number',
                'date_of_baptism',
                'church_status',
                'church_commitee',
                'educational_attainment',
                'course_or_program',
                'school_graduated',
                'year_graduated',
                'is_employed',
                'current_work',
                'last_work',
                'can_relocate',
            ]));

            // Upload Resume
            if ($request->hasFile('resume')) {
                $jobApplicantInfo->resume = $request->file('resume')->store('resumes', 'public');
            }

            // Upload Transcript of Records
            if ($request->hasFile('transcript_of_records')) {
                $jobApplicantInfo->transcript_of_records = $request->file('transcript_of_records')->store('transcripts', 'public');
            }

            // Save Job Application
            $jobApplicantInfo->save();

            $jobApplicant->load(['jobApplicantInformation']);

            return new JobApplicantResource($jobApplicant);
        } catch (Exception $e) {
            Log::error('Failed to update job applicant form', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function submitJobApplicationForm()
    {
        try {
            $jobApplication = auth('ats')->user();

            $jobApplicationProgress = $jobApplication->jobApplicationProgress;
            $updateFirstPhase = $jobApplicationProgress->firstWhere('job_application_phase_id', 1);

            // Set status to pending for shortlisting
            $updateFirstPhase->status = 'pending';
            $updateFirstPhase->save();


            return $updateFirstPhase;
        } catch (Exception $e) {

            throw $e;
        }
    }

    public function getApplicationById(int $applicationId)
    {
        try {
            $jobApplication = JobApplication::with([
                'jobApplicant.jobApplicantInformation',
                'jobSelectionOptions',
            ])->findOrFail($applicationId);

            return new JobApplicationWithInfoResource($jobApplication);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("Job Application NOt found!");
        } catch (Exception $e) {
            Log::error('Failed to retrieve job applicant by application ID', [
                'application_id' => $applicationId,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
