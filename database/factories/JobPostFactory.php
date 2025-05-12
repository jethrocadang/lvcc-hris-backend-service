<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JobPostFactory extends Factory
{
    public function definition(): array
    {
        $teachingJobs = [
            ['title' => 'Assistant Professor - Computer Science', 'icon' => 'GraduationCap'],
            ['title' => 'Instructor - Mathematics', 'icon' => 'GraduationCap'],
            ['title' => 'Lecturer - English Language', 'icon' => 'GraduationCap'],
            ['title' => 'Professor - Business Administration', 'icon' => 'GraduationCap'],
        ];

        $nonTeachingJobs = [
            ['title' => 'Registrar Clerk', 'icon' => 'FileText'],
            ['title' => 'Guidance Counselor', 'icon' => 'UserCircle'],
            ['title' => 'Library Assistant', 'icon' => 'BookOpen'],
            ['title' => 'IT Support Specialist', 'icon' => 'Laptop'],
            ['title' => 'HR Officer', 'icon' => 'Users'],
        ];

        $isTeaching = $this->faker->boolean();
        $job = $this->faker->randomElement($isTeaching ? $teachingJobs : $nonTeachingJobs);

        return [
            'work_type' => $this->faker->randomElement(['full-time', 'part-time', 'internship']),
            'job_type' => $this->faker->randomElement(['onsite', 'remote', 'hybrid']),
            'title' => $job['title'],
            'description' => $this->faker->paragraphs(3, true),
            'icon_url' => $job['icon'], // !Change this to icon_name
            'status' => $this->faker->randomElement(['open', 'closed']),
            'location' => 'Apalit, Pampanga',
            'schedule' => $this->faker->randomElement([
                'Monday to Friday, 8AM–5PM',
                'Night Shift',
                'Flexible'
            ]),
        ];
    }
}
