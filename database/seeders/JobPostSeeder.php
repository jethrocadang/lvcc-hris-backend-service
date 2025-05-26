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
            ['title' => 'Kindergarten Teacher', 'icon' => 'Baby', 'category' => 'teaching'],
            ['title' => 'Senior High School Coordinator', 'icon' => 'Users2', 'category' => 'teaching'],
            ['title' => 'Curriculum Developer', 'icon' => 'LayoutTemplate', 'category' => 'teaching'],
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
            ['title' => 'Marketing Specialist', 'icon' => 'Megaphone', 'category' => 'non-teaching'],
            ['title' => 'Purchasing Officer', 'icon' => 'ShoppingCart', 'category' => 'non-teaching'],
            ['title' => 'Data Analyst', 'icon' => 'BarChart', 'category' => 'non-teaching'],
            ['title' => 'Alumni Relations Officer', 'icon' => 'Handshake', 'category' => 'non-teaching'],
            ['title' => 'Legal Officer', 'icon' => 'Scale', 'category' => 'non-teaching'],
            ['title' => 'Events Coordinator', 'icon' => 'CalendarCheck2', 'category' => 'non-teaching'],
            ['title' => 'Grants and Scholarships Officer', 'icon' => 'Award', 'category' => 'non-teaching'],
        ];

        foreach ($jobs as $job) {
            JobPost::create([
                'work_type' => fake()->randomElement(['full-time', 'part-time', 'internship']),
                'job_type' => fake()->randomElement(['onsite', 'remote', 'hybrid']),
                'title' => $job['title'],
                'description' => self::htmlDescription($job['title']),
                'icon_name' => $job['icon'],
                'status' => fake()->randomElement(['open', 'closed']),
                'location' => 'Apalit',
                'category' => $job['category'],
            ]);
        }
    }

    private static function htmlDescription(string $title): string
    {
        $templates = [
            'High School Math Teacher' => [
                'summary' => 'Deliver engaging instruction in Algebra, Geometry, and Calculus to high school students.',
                'responsibilities' => [
                    'Create lesson plans aligned with national curriculum standards.',
                    'Assess and monitor student academic progress.',
                    'Facilitate classroom discussions and problem-solving activities.',
                    'Prepare students for math competitions and standardized exams.'
                ],
                'qualifications' => [
                    'Bachelor’s degree in Mathematics or Education.',
                    'Valid teaching license.',
                    'Experience teaching at secondary level preferred.',
                    'Strong communication and classroom management skills.'
                ]
            ],
            'Computer Science Professor' => [
                'summary' => 'Teach core computer science subjects such as data structures, algorithms, and software engineering.',
                'responsibilities' => [
                    'Conduct lectures, labs, and exams for CS courses.',
                    'Advise students in academic and career planning.',
                    'Participate in curriculum development and research projects.',
                    'Publish research in peer-reviewed journals.'
                ],
                'qualifications' => [
                    'PhD in Computer Science or related field.',
                    'University-level teaching experience.',
                    'Strong research portfolio.',
                    'Expertise in at least one programming language.'
                ]
            ],
            'Guidance and Counseling Teacher' => [
                'summary' => 'Provide academic, emotional, and career guidance to students.',
                'responsibilities' => [
                    'Hold individual counseling sessions.',
                    'Develop and deliver guidance modules.',
                    'Coordinate with parents and teachers for interventions.',
                    'Maintain confidential student records.'
                ],
                'qualifications' => [
                    'Bachelor’s degree in Psychology or Guidance Counseling.',
                    'PRC license as a Guidance Counselor.',
                    'Experience working with children/adolescents.',
                    'Empathy and strong interpersonal skills.'
                ]
            ],
            // Add more job-specific templates here...
        ];

        $job = $templates[$title] ?? [
            'summary' => 'Work in a dynamic educational environment fulfilling assigned duties and supporting institutional goals.',
            'responsibilities' => [
                'Perform job-specific tasks diligently.',
                'Collaborate with team members and supervisors.',
                'Adhere to school policies and ethical standards.',
                'Engage in continued professional development.'
            ],
            'qualifications' => [
                'Relevant degree or certification.',
                'Prior experience in a similar role is a plus.',
                'Strong organizational and communication skills.',
                'Ability to work independently and in teams.'
            ]
        ];

        $resList = collect($job['responsibilities'])->map(fn($r) => "<li>$r</li>")->implode('');
        $qualList = collect($job['qualifications'])->map(fn($q) => "<li>$q</li>")->implode('');

        return "<h1>{$title}</h1>"
            . "<p><strong>Job Summary:</strong> {$job['summary']}</p>"
            . "<p><strong>Responsibilities:</strong></p><ul>{$resList}</ul>"
            . "<p><strong>Qualifications:</strong></p><ul>{$qualList}</ul>"
            . "<p><strong>Application Instructions:</strong> Submit your resume and cover letter to <em>hr@school.edu.ph</em> or via our job portal.</p>";
    }
}
