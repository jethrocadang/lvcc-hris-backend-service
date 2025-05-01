<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobApplicationPhases;

class JobApplicationPhasesSeeder extends Seeder
{
    public function run(): void
    {
        $phases = [
            [
                'name' => 'Email Verification',
                'sequence_order' => 1,
                'description' => 'The applicant must verify their email address before proceeding with the application.'
            ],
            [
                'name' => 'Applicant Information',
                'sequence_order' => 2,
                'description' => 'The applicant is required to complete personal and background information.'
            ],
            [
                'name' => 'Initial Screening',
                'sequence_order' => 3,
                'description' => 'The applicant selects an available time slot for the initial interview screening.'
            ],
            [
                'name' => 'Behavioral Interview',
                'sequence_order' => 4,
                'description' => 'The applicant undergoes a behavioral interview to assess personality and cultural fit.'
            ],
            [
                'name' => 'Technical Interview (Non-Teaching)',
                'sequence_order' => 5,
                'description' => 'The applicant will undergo a technical interview to evaluate skills relevant to a non-teaching role.'
            ],
            [
                'name' => 'Teaching Demo (Teaching)',
                'sequence_order' => 5,
                'description' => 'The applicant will conduct a teaching demo to assess their instructional ability and subject knowledge.'
            ],
            [
                'name' => 'Management Interview',
                'sequence_order' => 6,
                'description' => 'The final interview with management to determine overall suitability and alignment.'
            ],
            [
                'name' => 'Onboarding',
                'sequence_order' => 7,
                'description' => 'Successful applicants begin the onboarding process for integration into the organization.'
            ],
        ];

        foreach ($phases as $phase) {
            JobApplicationPhases::updateOrCreate(
                ['name' => $phase['name']],
                [
                    'description' => $phase['description'],
                    'email_template_id' => null,
                    'sequence_order' => $phase['sequence_order'],
                ]
            );
        }
    }
}
