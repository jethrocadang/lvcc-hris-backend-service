<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;
use App\Models\JobApplicationPhases;

class LinkEmailTemplatesToPhasesSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'Email Verification'               => 'Verify Your Email',
            'Applicant Information'            => 'Complete Your Applicant Information',
            'Initial Screening'                => 'Schedule Your Interview',
            'Behavioral Interview'             => 'Behavioral Interview Scheduled',
            'Technical Interview (Non-Teaching)' => 'Technical Interview Invitation',
            'Teaching Demo (Teaching)'         => 'Teaching Demo Invitation',
            'Management Interview'             => 'Final Management Interview',
            'Onboarding'                       => 'Welcome to the Team!',
        ];

        foreach ($mapping as $phaseName => $templateTitle) {
            $template = EmailTemplate::where('email_title', $templateTitle)->first();

            if (!$template) {
                //Log if a template is missing
                logger()->warning("Email template not found for: $templateTitle");
                continue;
            }

            JobApplicationPhases::where('name', $phaseName)->update([
                'email_template_id' => $template->id
            ]);
        }
    }
}
