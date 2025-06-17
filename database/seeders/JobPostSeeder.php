<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPost;
use App\Models\Department;

class JobPostSeeder extends Seeder
{
    public function run(): void
    {
        // Get all departments from the landlord database
        $departments = Department::all();

        // If no departments exist, we can't proceed
        if ($departments->isEmpty()) {
            $this->command->error('No departments found in the landlord database. Please run the DepartmentSeeder first.');
            return;
        }

        $jobs = [
            ['title' => 'High School Math Teacher', 'icon' => 'GraduationCap', 'category' => 'teaching', 'department' => 'Basic Education'],
            ['title' => 'College Biology Instructor', 'icon' => 'GraduationCap', 'category' => 'teaching', 'department' => 'Higher Education'],
            ['title' => 'Computer Science Professor', 'icon' => 'Laptop', 'category' => 'teaching', 'department' => 'Higher Education'],
            ['title' => 'English Language Lecturer', 'icon' => 'BookOpen', 'category' => 'teaching', 'department' => 'Higher Education'],
            ['title' => 'Senior Chemistry Faculty', 'icon' => 'FlaskRound', 'category' => 'teaching', 'department' => 'Higher Education'],
            ['title' => 'Physical Education Coach', 'icon' => 'Dumbbell', 'category' => 'teaching', 'department' => 'Basic Education'],
            ['title' => 'Art and Design Mentor', 'icon' => 'Paintbrush', 'category' => 'teaching', 'department' => 'Higher Education'],
            ['title' => 'Guidance and Counseling Teacher', 'icon' => 'UserCircle', 'category' => 'teaching', 'department' => 'Prefect of Student Affairs'],
            ['title' => 'Special Education Specialist', 'icon' => 'HeartHandshake', 'category' => 'teaching', 'department' => 'Basic Education'],
            ['title' => 'Academic Research Coordinator', 'icon' => 'FileText', 'category' => 'teaching', 'department' => 'Higher Education'],
            ['title' => 'Kindergarten Teacher', 'icon' => 'Baby', 'category' => 'teaching', 'department' => 'Basic Education'],
            ['title' => 'Senior High School Coordinator', 'icon' => 'Users2', 'category' => 'teaching', 'department' => 'Basic Education'],
            ['title' => 'Curriculum Developer', 'icon' => 'LayoutTemplate', 'category' => 'teaching', 'department' => 'Quality Assurance & Compliance Office'],
            ['title' => 'Library Assistant', 'icon' => 'Book', 'category' => 'non-teaching', 'department' => 'Library'],
            ['title' => 'Administrative Secretary', 'icon' => 'NotebookPen', 'category' => 'non-teaching', 'department' => 'Administration'],
            ['title' => 'Registrar Staff', 'icon' => 'ClipboardList', 'category' => 'non-teaching', 'department' => 'Registration and Admissions'],
            ['title' => 'IT Support Technician', 'icon' => 'Cpu', 'category' => 'non-teaching', 'department' => 'Management Information Systems'],
            ['title' => 'Campus Security Officer', 'icon' => 'Shield', 'category' => 'non-teaching', 'department' => 'General Administrative Services'],
            ['title' => 'Human Resources Assistant', 'icon' => 'Users', 'category' => 'non-teaching', 'department' => 'Human Resource'],
            ['title' => 'Finance Officer', 'icon' => 'Wallet', 'category' => 'non-teaching', 'department' => 'Finance & Accounting'],
            ['title' => 'School Nurse', 'icon' => 'Stethoscope', 'category' => 'non-teaching', 'department' => 'Prefect of Student Affairs'],
            ['title' => 'Maintenance Personnel', 'icon' => 'Wrench', 'category' => 'non-teaching', 'department' => 'General Administrative Services'],
            ['title' => 'Admissions Coordinator', 'icon' => 'UserPlus', 'category' => 'non-teaching', 'department' => 'Registration and Admissions'],
            ['title' => 'Marketing Specialist', 'icon' => 'Megaphone', 'category' => 'non-teaching', 'department' => 'Administration'],
            ['title' => 'Purchasing Officer', 'icon' => 'ShoppingCart', 'category' => 'non-teaching', 'department' => 'Finance & Accounting'],
            ['title' => 'Data Analyst', 'icon' => 'BarChart', 'category' => 'non-teaching', 'department' => 'Management Information Systems'],
            ['title' => 'Alumni Relations Officer', 'icon' => 'Handshake', 'category' => 'non-teaching', 'department' => 'Prefect of Student Affairs'],
            ['title' => 'Legal Officer', 'icon' => 'Scale', 'category' => 'non-teaching', 'department' => 'Administration'],
            ['title' => 'Events Coordinator', 'icon' => 'CalendarCheck2', 'category' => 'non-teaching', 'department' => 'Prefect of Student Affairs'],
            ['title' => 'Grants and Scholarships Officer', 'icon' => 'Award', 'category' => 'non-teaching', 'department' => 'Finance & Accounting'],
        ];

        // Create a mapping of department names to IDs for quick lookup
        $departmentMap = $departments->pluck('id', 'name')->toArray();

        foreach ($jobs as $job) {
            // Find the department ID based on the department name
            $departmentId = $departmentMap[$job['department']] ?? null;

            // If we can't find a matching department, assign to a random department
            if (!$departmentId) {
                $departmentId = $departments->random()->id;
            }

            JobPost::create([
                'department_id' => $departmentId,
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
                    'Bachelor\'s degree in Mathematics or Education.',
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
                    'Bachelor\'s degree in Psychology or Guidance Counseling.',
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
