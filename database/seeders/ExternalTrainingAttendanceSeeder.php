<?php

namespace Database\Seeders;

use App\Models\ExternalTrainingAttendance;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ExternalTrainingAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get employee users - adjust this query based on your actual user structure
        $employees = User::all()->take(5);

        if ($employees->isEmpty()) {
            $this->command->warn('No users found. Using ID 1 as fallback.');
            $employeeIds = [1]; // Fallback to ID 1 if no users exist
        } else {
            $employeeIds = $employees->pluck('id')->toArray();
        }

        // Training types
        $trainingTypes = ['compliance', 'external', 'other'];

        // Training modes
        $trainingModes = ['online', 'in-person', 'hybrid'];

        // Training providers
        $providers = [
            'LinkedIn Learning',
            'Coursera',
            'Udemy',
            'EdX',
            'Microsoft Learn'
        ];

        // Create 10 external training attendance records
        foreach (range(1, 10) as $index) {
            $startDate = $faker->dateTimeBetween('-1 year', '-1 month');
            $endDate = $faker->dateTimeBetween($startDate, 'now');

            ExternalTrainingAttendance::create([
                'employee_id' => $faker->randomElement($employeeIds),
                'training_type' => $faker->randomElement($trainingTypes),
                'title' => $faker->sentence(4),
                'description' => $faker->paragraph(2),
                'provider' => $faker->randomElement($providers),
                'training_mode' => $faker->randomElement($trainingModes),
                'location' => $faker->randomElement($trainingModes) === 'online' ? 'Virtual' : $faker->city,
                'certificate_url' => null, // Certificates would be uploaded files in real usage
                'hours_completed' => $faker->numberBetween(1, 40),
                'date_started' => $startDate,
                'date_completed' => $endDate,
            ]);
        }
    }
}
