<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPost;

class JobPostSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            ['title' => 'High School Math Teacher', 'icon' => 'GraduationCap', 'category' => 'teaching'],
            ['title' => 'College Biology Instructor', 'icon' => 'GraduationCap', 'category' => 'teaching'],
            ['title' => 'Computer Science Professor', 'icon' => 'Laptop', 'category' => 'teaching'],
            ['title' => 'English Language Lecturer', 'icon' => 'BookOpen', 'category' => 'teaching'],
            ['title' => 'Senior Chemistry Faculty', 'icon' => 'FlaskRound', 'category' => 'teaching'],
            ['title' => 'Physical Education Coach', 'icon' => 'Dumbbell', 'category' => 'teaching'],
            ['title' => 'Art and Design Mentor', 'icon' => 'Paintbrush', 'category' => 'teaching'],
            ['title' => 'Guidance and Counseling Teacher', 'icon' => 'UserCircle', 'category' => 'teaching'],
            ['title' => 'Special Education Specialist', 'icon' => 'HeartHandshake', 'category' => 'teaching'],
            ['title' => 'Academic Research Coordinator', 'icon' => 'FileText', 'category' => 'teaching'],
            ['title' => 'Library Assistant', 'icon' => 'Book', 'category' => 'non-teaching'],
            ['title' => 'Administrative Secretary', 'icon' => 'NotebookPen', 'category' => 'non-teaching'],
            ['title' => 'Registrar Staff', 'icon' => 'ClipboardList', 'category' => 'non-teaching'],
            ['title' => 'IT Support Technician', 'icon' => 'Cpu', 'category' => 'non-teaching'],
            ['title' => 'Campus Security Officer', 'icon' => 'Shield', 'category' => 'non-teaching'],
            ['title' => 'Human Resources Assistant', 'icon' => 'Users', 'category' => 'non-teaching'],
            ['title' => 'Finance Officer', 'icon' => 'Wallet', 'category' => 'non-teaching'],
            ['title' => 'School Nurse', 'icon' => 'Stethoscope', 'category' => 'non-teaching'],
            ['title' => 'Maintenance Personnel', 'icon' => 'Wrench', 'category' => 'non-teaching'],
            ['title' => 'Admissions Coordinator', 'icon' => 'UserPlus', 'category' => 'non-teaching'],
        ];

        foreach ($jobs as $job) {
            JobPost::create([
                'work_type' => fake()->randomElement(['full-time', 'part-time', 'internship']),
                'job_type' => fake()->randomElement(['onsite', 'remote', 'hybrid']),
                'title' => $job['title'],
                'description' => self::htmlDescription(),
                'icon_name' => $job['icon'],
                'status' => fake()->randomElement(['open', 'closed']),
                'location' => 'Apalit',
                'category' => $job['category'],
            ]);
        }
    }

    private static function htmlDescription(): string
    {
        $bullets = collect(range(1, 3))
            ->map(fn() => '<li>' . fake()->sentence() . '</li>')
            ->implode('');

        return "<h1>" . fake()->catchPhrase() . "</h1>"
             . "<p>" . fake()->paragraph() . "</p>"
             . "<ul>{$bullets}</ul>"
             . "<p><strong>" . fake()->bs() . "</strong></p>";
    }
}
