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
use Faker\Factory as Faker;

class JobApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_PH'); // Use Philippine locale for more realistic data

        $religions = ['MCGI', 'Catholic', 'Other', 'Protestant'];
        $mcgiLocalesAndDivisions = ['Metro Manila South', 'Metro Manila North', 'Central Luzon', 'Southern Luzon', 'Bicol Region', 'Visayas', 'Mindanao']; // Added more specific MCGI locales
        $mcgiChurchStatuses = ['Active', 'Inactive', 'Suspended'];
        $mcgiChurchCommittees = ['MCGI GCOs', 'MCGI Servant', 'MCGI Music Ministry', 'MCGI Teatro Kristyano', 'MCGI Photoville', 'MCGI Productions']; // More specific MCGI committees

        $educationalAttainments = ['Doctorate Degree', 'Masters Degree', 'Bachelors Degree', 'Vocational', 'Vocational', 'Undergraduate'];
        $coursesOrPrograms = ['Computer Science', 'Information Technology', 'Accountancy', 'Business Administration', 'Marketing', 'Education', 'Nursing', 'Engineering'];
        $schools = ['University of the Philippines', 'De La Salle University', 'Ateneo de Manila University', 'University of Santo Tomas', 'FEU Institute of Technology', 'Polytechnic University of the Philippines'];
        $currentWorks = ['Software Engineer', 'Data Analyst', 'Marketing Specialist', 'HR Coordinator', 'Customer Service Representative', 'Accountant', 'Teacher'];
        $lastWorks = ['Junior Developer', 'Intern', 'Trainee', 'Assistant'];
        $reviewerRemarksOptions = [
            'Strong candidate, good potential.',
            'Needs further assessment on technical skills.',
            'Excellent communication skills.',
            'Background aligns well with requirements.',
            'Consider for the next phase.',
            'Good foundational knowledge.',
            'Impressive work experience.',
            'Very enthusiastic and engaged during interview.',
            'Potential for leadership role.',
            'Requires further evaluation on soft skills.',
        ];

        // Ensure these IDs exist in your database or adjust as needed
        $jobId = 1; // Assuming a job with ID 1 exists
        $jobApplicationPhaseId = 1; // Assuming a job application phase with ID 1 exists
        $reviewerId = 1; // Assuming an admin/user with ID 1 exists

        // Create multiple applicants with info and applications
        for ($i = 1; $i <= 10; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $email = strtolower($firstName . '.' . $lastName . $i) . '@example.test';
            $selectedReligion = $faker->randomElement($religions);

            $applicant = JobApplicant::create([
                'first_name' => $firstName,
                'middle_name' => $faker->randomLetter,
                'last_name' => $lastName,
                'email' => $email,
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
                'avatar_url' => null, // You might want to use Faker for a dummy avatar URL here if needed
                'verification_token' => Str::random(32),
            ]);

            $isEmployed = $faker->boolean(70); // 70% chance of being currently employed
            $yearGraduated = $faker->numberBetween(2010, 2023);

            $applicantInfoData = [
                'job_applicant_id' => $applicant->id,
                'current_address' => $faker->address,
                'contact_number' => $faker->mobileNumber,
                'religion' => $selectedReligion,
                'educational_attainment' => $faker->randomElement($educationalAttainments),
                'course_or_program' => $faker->randomElement($coursesOrPrograms),
                'school_graduated' => $faker->randomElement($schools),
                'year_graduated' => $yearGraduated,
                'is_employed' => $isEmployed,
                'current_work' => $isEmployed ? $faker->randomElement($currentWorks) : null,
                'last_work' => $faker->randomElement($lastWorks),
                'resume' => null, // In a real scenario, you'd generate a path or mock a file upload
                'transcript_of_records' => null, // Same as above
                'can_relocate' => $faker->boolean(),
            ];

            // Conditionally add MCGI specific data
            if ($selectedReligion === 'MCGI') {
                $applicantInfoData['locale_and_division'] = $faker->randomElement($mcgiLocalesAndDivisions);
                $applicantInfoData['servant_name'] = $faker->name;
                $applicantInfoData['servant_contact_number'] = $faker->mobileNumber;
                $applicantInfoData['date_of_baptism'] = Carbon::parse($faker->dateTimeBetween('-15 years', '-2 years')->format('Y-m-d'));
                $applicantInfoData['church_status'] = $faker->randomElement($mcgiChurchStatuses);
                $applicantInfoData['church_commitee'] = $faker->randomElement($mcgiChurchCommittees);
            } else {
                // Set to null or a default empty string for non-MCGI applicants
                $applicantInfoData['locale_and_division'] = null;
                $applicantInfoData['servant_name'] = null;
                $applicantInfoData['servant_contact_number'] = null;
                $applicantInfoData['date_of_baptism'] = null;
                $applicantInfoData['church_status'] = null; // Use general statuses for others
                $applicantInfoData['church_commitee'] = null; // Use general committees for others
            }

            JobApplicantInformation::create($applicantInfoData);

            $application = JobApplication::create([
                'job_applicant_id' => $applicant->id,
                'portal_token' => Str::uuid(),
            ]);

            JobSelectionOption::create([
                'job_application_id' => $application->id,
                'job_id' => $jobId,
                'priority' => 1,
                'status' => null, // Null initially, will be updated during selection process
            ]);

            JobApplicationProgress::create([
                'job_application_id' => $application->id,
                'job_application_phase_id' => $jobApplicationPhaseId,
                'reviewed_by' => $reviewerId,
                'reviewer_remarks' => $faker->randomElement($reviewerRemarksOptions),
                'status' => $faker->randomElement(['in-progress', 'pending', 'accepted']), // Simulate different statuses
                'start_date' => Carbon::now()->subDays($faker->numberBetween(1, 21)), // Start date up to 3 weeks ago
                'end_date' => Carbon::now()->addDays($faker->numberBetween(1, 14)), // End date up to 2 weeks from now
            ]);
        }
    }
}
