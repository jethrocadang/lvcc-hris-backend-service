<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobApplicant;
use App\Models\JobApplicantInformation;
use App\Models\JobApplication;
use App\Models\JobSelectionOption;
use App\Models\JobApplicationProgress;
use Illuminate\Support\Str;
use Carbon\Carbon;

class JobApplicationSeeder extends Seeder
{
    public function run(): void
    {
        // Create multiple applicants with info and applications
        for ($i = 1; $i <= 5; $i++) {
            $applicant = JobApplicant::create([
                'first_name' => "First{$i}",
                'middle_name' => "M",
                'last_name' => "Last{$i}",
                'email' => "applicant{$i}@example.com",
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
                'avatar_url' => null,
                'verification_token' => Str::random(32),
            ]);

            $applicantInfo = JobApplicantInformation::create([
                'job_applicant_id' => $applicant->id,
                'current_address' => "123 Main St, City {$i}",
                'contact_number' => "0917123456{$i}",
                'religion' => 'MCGI',
                'locale_and_division' => 'Metro',
                'servant_name' => 'Servant Name',
                'servant_contact_number' => '09179876543',
                'date_of_baptism' => Carbon::parse('2015-01-01'),
                'church_status' => 'Active',
                'church_commitee' => 'Youth',
                'educational_attainment' => 'College',
                'course_or_program' => 'IT',
                'school_graduated' => 'Example University',
                'year_graduated' => 2020,
                'is_employed' => true,
                'current_work' => 'Developer',
                'last_work' => 'Intern',
                'resume' => null,
                'transcript_of_records' => null,
                'can_relocate' => true,
            ]);

            $application = JobApplication::create([
                'job_applicant_id' => $applicant->id,
                'portal_token' => Str::uuid(),
            ]);

            JobSelectionOption::create([
                'job_application_id' => $application->id,
                'job_id' => 1, // assumes job_id = 1 exists
                'priority' => 1,
                'status' => null,
            ]);

            

            JobApplicationProgress::create([
                'job_application_id' => $application->id,
                'job_application_phase_id' => 1, // assumes job_application_phase_id = 1 exists
                'reviewed_by' => 1, // assumes admin/user with ID = 1 exists
                'reviewer_remarks' => 'Looks promising',
                'status' => 'in-progress',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(7),
            ]);
        }
    }
}
