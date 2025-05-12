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

        // Define the application phases in order
        $phases = [
            'Verified Email',
            'Applicant Information',
            'Initial Screening',
            'Behavioral Interview',
            'Demo or Technical',
            'Management Interview',
        ];

        foreach ($phases as $index => $phaseName) {
            // Create acceptance email template
            $acceptanceId = DB::table('ats_email_templates')->insertGetId([
                'user_id' => null,
                'type' => 'acceptance',
                'subject' => "You're moving forward: {$phaseName}",
                'body' => "Congratulations! You’ve advanced to the {$phaseName} phase.",
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Create rejection email template
            $rejectionId = DB::table('ats_email_templates')->insertGetId([
                'user_id' => null,
                'type' => 'rejection',
                'subject' => "Application Update: {$phaseName}",
                'body' => "Thank you for applying. Unfortunately, you will not proceed past the {$phaseName} phase.",
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Create the application phase
            DB::table('job_application_phases')->insert([
                'acceptance_email_template_id' => $acceptanceId,
                'rejection_email_template_id' => $rejectionId,
                'title' => $phaseName, // previously "title"
                'description' => "This is the {$phaseName} phase of the hiring process.",
                'sequence_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
