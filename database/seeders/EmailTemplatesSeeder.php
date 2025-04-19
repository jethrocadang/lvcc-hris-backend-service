<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'version' => 'v1',
                'template_type' => 'ATS',
                'email_title' => 'Verify Your Email',
                'email_body' => '<p>Dear {{ name }},</p><p>Please verify your email by clicking the link below:</p><p><a href="{{ verification_link }}">Verify Email</a></p>'
            ],
            [
                'version' => 'v1',
                'template_type' => 'ATS',
                'email_title' => 'Complete Your Applicant Information',
                'email_body' => '<p>Hi {{ name }},</p><p>Don’t forget to complete your applicant profile. You can continue your application here:</p><p><a href="{{ portal_link }}">Continue Application</a></p>'
            ],
            [
                'version' => 'v1',
                'template_type' => 'ATS',
                'email_title' => 'Schedule Your Interview',
                'email_body' => '<p>Hello {{ name }},</p><p>Initial screening is ready. Please pick your interview slot:</p><p><a href="{{ schedule_link }}">Select Slot</a></p>'
            ],
            [
                'version' => 'v1',
                'template_type' => 'ATS',
                'email_title' => 'Behavioral Interview Scheduled',
                'email_body' => '<p>Hi {{ name }},</p><p>You’ve been scheduled for a behavioral interview. Please review the details in your applicant portal.</p>'
            ],
            [
                'version' => 'v1',
                'template_type' => 'ATS',
                'email_title' => 'Technical Interview Invitation',
                'email_body' => '<p>Hi {{ name }},</p><p>You’ve advanced to the technical interview stage for a non-teaching position. Please check your schedule and prepare accordingly.</p>'
            ],
            [
                'version' => 'v1',
                'template_type' => 'ATS',
                'email_title' => 'Teaching Demo Invitation',
                'email_body' => '<p>Hello {{ name }},</p><p>You’ve advanced to the teaching demonstration stage. Please review your assigned topic and be prepared to deliver your demo as scheduled.</p>'
            ],

            [
                'version' => 'v1',
                'template_type' => 'ATS',
                'email_title' => 'Final Management Interview',
                'email_body' => '<p>Dear {{ name }},</p><p>This is your final interview with management. Please prepare accordingly. Details are available in the portal.</p>'
            ],
            [
                'version' => 'v1',
                'template_type' => 'ATS',
                'email_title' => 'Welcome to the Team!',
                'email_body' => '<p>Congratulations {{ name }}!</p><p>You’ve made it! Please check your onboarding tasks here: <a href="{{ onboarding_link }}">Onboarding Portal</a></p>'
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['email_title' => $template['email_title']],
                $template
            );
        }
    }
}
