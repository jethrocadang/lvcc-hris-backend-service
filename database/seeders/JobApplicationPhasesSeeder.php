<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobApplicationPhasesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $phases = [
            'Application Submission' => [
                'slug' => 'phase-one',
                'acceptance_subject' => 'Email Verified',
                'acceptance_body' => "Hi there,\n\nThank you for verifying your email address. Your application is now officially in our system. You can  now access the application portal and fillout the neccessary information. We appreciate your interest in joining our team and will update you as your application progresses.\n\nBest regards,\nThe Hiring Team",
            ],
            'Shortlisted' => [
                'slug' => 'phase-two',
                'acceptance_subject' => 'You’ve Been Shortlisted!',
                'acceptance_body' => "Dear Applicant,\n\nWe’re pleased to inform you that you’ve been shortlisted for the next steps in our hiring process. Your background and experience stood out, and we look forward to getting to know you better.\n\nStay tuned for more details.\n\nBest,\nRecruitment Team",
                'rejection_subject' => 'Application Update – Not Moving Forward',
                'rejection_body' => "Dear Applicant,\n\nThank you for your interest in the position. After careful consideration, we have decided not to move forward with your application at this time. We wish you the best in your job search.\n\nWarm regards,\nHiring Team",
            ],
            'Initial Screening' => [
                'slug' => 'phase-three',
                'acceptance_subject' => 'Next Step: Initial Screening Scheduled',
                'acceptance_body' => "Hi [Applicant Name],\n\nWe’re happy to move you forward to the initial screening phase. One of our team members will be reaching out shortly to conduct a brief screening interview.\n\nWe’re excited to learn more about you!\n\nSincerely,\nTalent Acquisition Team",
                'rejection_subject' => 'Application Outcome: Initial Screening',
                'rejection_body' => "Dear [Applicant Name],\n\nThank you for participating in the initial screening process. After reviewing all candidates, we regret to inform you that we will not be moving forward with your application.\n\nWe appreciate your interest and wish you every success.\n\nKind regards,\nRecruitment Team",
            ],
            'Behavioral Interview' => [
                'slug' => 'phase-four',
                'acceptance_subject' => 'You’re Moving Forward: Behavioral Interview',
                'acceptance_body' => "Hi [Applicant Name],\n\nGreat news! You've advanced to the behavioral interview stage. This phase helps us understand how you approach challenges and collaborate with others.\n\nWe'll send scheduling options soon.\n\nWarm regards,\nHiring Coordinator",
                'rejection_subject' => 'Thank You for Interviewing',
                'rejection_body' => "Dear [Applicant Name],\n\nThank you for your time and for sharing your experiences during the behavioral interview. Unfortunately, we will not be proceeding with your application.\n\nWe’re grateful for your interest in our team.\n\nAll the best,\nHiring Team",
            ],
            'Teaching Demo' => [
                'slug' => 'phase-five-demo',
                'acceptance_subject' => 'Teaching Demo Invitation',
                'acceptance_body' => "Dear [Applicant Name],\n\nCongratulations! You’ve progressed to the teaching demonstration phase. We’re excited to see your instructional approach and interaction style.\n\nWe’ll follow up with next steps and requirements.\n\nBest,\nAcademic Recruitment Team",
                'rejection_subject' => 'Teaching Demo Review Outcome',
                'rejection_body' => "Hi [Applicant Name],\n\nThank you for preparing and delivering your teaching demo. After careful consideration, we have decided not to proceed further with your application.\n\nWe truly value the effort you put in.\n\nSincerely,\nAcademic Recruitment Team",
            ],
            'Technical Interview' => [
                'slug' => 'phase-five-technical',
                'acceptance_subject' => 'Technical Interview Invitation',
                'acceptance_body' => "Hi [Applicant Name],\n\nWe’re excited to invite you to the technical interview phase. This session will assess your problem-solving skills and technical expertise.\n\nFurther details and preparation tips will be shared shortly.\n\nRegards,\nTech Hiring Team",
                'rejection_subject' => 'Technical Interview Update',
                'rejection_body' => "Dear [Applicant Name],\n\nThank you for participating in the technical interview. After reviewing your performance, we’ve decided not to move forward.\n\nWe appreciate your time and effort.\n\nSincerely,\nTechnical Recruitment Team",
            ],
            'Management Interview' => [
                'slug' => 'phase-six',
                'acceptance_subject' => 'Management Interview Phase',
                'acceptance_body' => "Dear [Applicant Name],\n\nWe’re thrilled to move you into the management interview stage. This is an opportunity to meet with leadership and discuss how you align with our team’s goals and culture.\n\nLooking forward to it!\n\nWarm regards,\nPeople Operations",
                'rejection_subject' => 'Interview Update – Final Stages',
                'rejection_body' => "Dear [Applicant Name],\n\nThank you for meeting with our leadership team. Unfortunately, we have chosen to move forward with another candidate at this time.\n\nWe wish you all the best in your future endeavors.\n\nSincerely,\nPeople Team",
            ],
            'Onboarding' => [
                'slug' => 'phase-seven',
                'acceptance_subject' => 'Welcome Aboard!',
                'acceptance_body' => "Dear [Applicant Name],\n\nWe’re excited to officially welcome you to the team! You’ve successfully completed the hiring process. Our onboarding coordinator will reach out with your start date and next steps.\n\nCongratulations and welcome!\n\nWarmly,\nHR Team",
                'rejection_subject' => 'Hiring Process Closure',
                'rejection_body' => "Dear [Applicant Name],\n\nWhile we greatly appreciate your participation throughout the hiring journey, we’ve decided not to proceed with your application at this stage.\n\nWe sincerely thank you and wish you success ahead.\n\nBest,\nHiring Team",
            ],
        ];

        $sequence = 1;

        foreach ($phases as $phaseName => $template) {
            $acceptanceId = DB::table('ats_email_templates')->insertGetId([
                'user_id' => null,
                'type' => 'acceptance',
                'subject' => $template['acceptance_subject'],
                'body' => $template['acceptance_body'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $rejectionId = null;
            if (isset($template['rejection_subject'], $template['rejection_body'])) {
                $rejectionId = DB::table('ats_email_templates')->insertGetId([
                    'user_id' => null,
                    'type' => 'rejection',
                    'subject' => $template['rejection_subject'],
                    'body' => $template['rejection_body'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::table('job_application_phases')->insert([
                'acceptance_email_template_id' => $acceptanceId,
                'rejection_email_template_id' => $rejectionId,
                'title' => $phaseName,
                'slug' => $template['slug'],
                'description' => "This is the {$phaseName} phase of the hiring process.",
                'sequence_order' => $sequence++,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
