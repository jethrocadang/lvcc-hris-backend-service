<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\EmployeeInformation;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Create or get a department position
        $departmentPositionId = DB::table('department_positions')->first()?->id ?? DB::table('department_positions')->insertGetId([
            'name' => 'Random Department',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create 10 random employees
        for ($i = 0; $i < 10; $i++) {
            // Generate name parts
            $firstName = $faker->firstName;
            $middleName = $faker->boolean ? $faker->firstName : null;
            $lastName = $faker->lastName;

            // Create user
            $user = User::factory()->create([
                'name' => "$firstName $lastName",
                'email' => strtolower($firstName . '.' . $lastName . $i . '@laverdad.edu.ph'),
                'password' => Hash::make('password'),
                'google_id' => Str::uuid(),
                'avatar_url' => $faker->imageUrl(200, 200, 'people'),
            ]);

            // Create employee information
            $employeeInfo = EmployeeInformation::create([
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'date_hired' => $faker->dateTimeBetween('-5 years', 'now'),
                'contact_number' => '09' . $faker->randomNumber(9, true),
                'current_address' => $faker->address,
                'permanent_address' => $faker->address,
                'birth_date' => $faker->date('Y-m-d', '-20 years'),
                'baptism_date' => $faker->boolean ? $faker->date('Y-m-d', '-18 years') : null,
                'religion' => $faker->randomElement(['Catholic', 'Christian', 'Born Again', null]),
                'gender' => $faker->randomElement(['male', 'female']),
                'marital_status' => $faker->randomElement(['married', 'widowed', 'separated', 'single']),
                'educational_attainment' => $faker->randomElement([
                    'High School Graduate', 'College Graduate', 'Master\'s Degree', null
                ]),
                'license' => $faker->boolean ? 'PRC-' . $faker->randomNumber(6) : null,
                'tin_number' => $faker->boolean ? $faker->numerify('###-###-###') : null,
                'pagibig_number' => $faker->boolean ? $faker->numerify('####-####-####') : null,
                'sss_number' => $faker->boolean ? $faker->numerify('##-#######-#') : null,
                'philhealth_number' => $faker->boolean ? $faker->numerify('####-####-####') : null,
                'work_email' => strtolower($firstName . '.' . $lastName . $i . '@laverdad.edu.ph'),
                'personal_email' => $faker->boolean ? $faker->unique()->safeEmail : null,
            ]);

            // Create employee record
            Employee::create([
                'user_id' => $user->id,
                'department_position_id' => $departmentPositionId,
                'employee_information_id' => $employeeInfo->id,
                'employee_id' => strtoupper(Str::random(8)),
                'employee_type' => $faker->randomElement(['full-time', 'part-time', 'volunteer']),
                'employment_status' => $faker->randomElement(['regular', 'probationary']),
                'employment_category' => $faker->randomElement(['teaching', 'non-teaching']),
                'employee_status' => $faker->randomElement([
                    'active', 'resigned', 'terminated', 'contract_ended', 'on_leave', 'suspended'
                ]),
                'employment_end_date' => $faker->boolean ? $faker->dateTimeBetween('-1 year', 'now') : null,
                'latest_position_designation' => $faker->boolean ? $faker->jobTitle : null,
                'work_schedule' => $faker->boolean ? $faker->dateTimeThisMonth : null,
            ]);
        }
    }
}
