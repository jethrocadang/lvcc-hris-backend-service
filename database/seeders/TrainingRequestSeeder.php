<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TrainingRequest;
use Illuminate\Support\Carbon;

class TrainingRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assume employee role ID = 3, supervisor = 2, officer = 1 (adjust as needed)
        $employees = User::on('landlord')->role('Employee')->pluck('id');
        $supervisors = User::on('landlord')->role('Department Head')->pluck('id');
        $officers = User::on('landlord')->role('HR Officer')->pluck('id');


        if ($employees->isEmpty() || $supervisors->isEmpty() || $officers->isEmpty()) {
            $this->command->warn('No users with required roles found. Skipping TrainingRequest seeding.');
            return;
        }

        $statuses = ['approved', 'pending', 'rejected'];
        $formats = ['online', 'in-person', 'blended'];

        foreach (range(1, 20) as $i) {
            $supervisorStatus = fake()->randomElement($statuses);
            $officerStatus = fake()->randomElement($statuses);

            TrainingRequest::create([
                'employee_id' => $employees->random(),
                'supervisor_id' => $supervisors->random(),
                'officer_id' => $officers->random(),
                'subject' => fake()->sentence(),
                'description' => fake()->paragraph(),
                'justification' => fake()->paragraph(),
                'expected_outcome' => fake()->sentence(),
                'training_format' => fake()->randomElement($formats),
                'estimated_duration' => fake()->randomElement(['1 day', '3 days', '1 week', '2 weeks']),
                'supervisor_status' => $supervisorStatus,
                'supervisor_reviewed_at' => $supervisorStatus !== 'pending' ? Carbon::now()->subDays(rand(1, 5)) : null,
                'officer_status' => $officerStatus,
                'officer_reviewed_at' => $officerStatus !== 'pending' ? Carbon::now()->subDays(rand(1, 3)) : null,
                'request_status' => $supervisorStatus === 'rejected' || $officerStatus === 'rejected' ? 'rejected' : 'approved',
                'rejection_reason' => $supervisorStatus === 'rejected' || $officerStatus === 'rejected' ? fake()->sentence() : null,
            ]);
        }
    }
}
