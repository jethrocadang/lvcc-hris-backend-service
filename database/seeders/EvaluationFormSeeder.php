<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EvaluationForm;
use App\Models\EvaluationCategory;
use App\Models\EvaluationItem;
use App\Models\TrainingCourse;

class EvaluationFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some training courses to attach evaluation forms to
        $trainingCourses = TrainingCourse::take(3)->get();

        if ($trainingCourses->isEmpty()) {
            // If no courses exist, create a dummy course for the evaluation form
            $trainingCourse = TrainingCourse::create([
                'author_id' => 1, // Assuming user ID 1 exists
                'title' => 'Leadership and Management Training',
                'description' => 'Comprehensive leadership training program for new managers',
                'type' => 'specialized',
                'max_participants' => 30,
                'current_participants' => 0,
                'enrollment_deadline' => now()->addDays(30),
            ]);

            $trainingCourses = collect([$trainingCourse]);
        }

        // Create evaluation forms for each course
        foreach ($trainingCourses as $index => $course) {
            // Create different evaluation forms based on the course index
            switch ($index % 3) {
                case 0:
                    $this->createStandardEvaluationForm($course);
                    break;
                case 1:
                    $this->createTechnicalTrainingEvaluationForm($course);
                    break;
                case 2:
                    $this->createOnboardingEvaluationForm($course);
                    break;
            }
        }
    }

    /**
     * Create a leadership training evaluation form
     */
    private function createLeadershipEvaluationForm(TrainingCourse $course): void
    {
        // Create the main evaluation form
        $form = EvaluationForm::create([
            'employee_training_course_id' => $course->id,
            'title' => 'Leadership Training Program Evaluation',
            'is_active' => true,
        ]);

        // Create categories and their items
        $categories = [
            [
                'title' => 'Course Content',
                'items' => [
                    'The course objectives were clearly defined.',
                    'The topics covered were relevant to my role.',
                    'The course materials were well-organized and easy to follow.',
                    'The course content was appropriate for my level of experience.',
                    'The leadership concepts presented were practical and applicable to my work.'
                ]
            ],
            [
                'title' => 'Instructor Effectiveness',
                'items' => [
                    'The instructor demonstrated thorough knowledge of the subject matter.',
                    'The instructor was well-prepared for each session.',
                    'The instructor explained concepts clearly and effectively.',
                    'The instructor encouraged participation and questions.',
                    'The instructor provided helpful feedback during activities.'
                ]
            ],
            [
                'title' => 'Learning Environment',
                'items' => [
                    'The training facilities were conducive to learning.',
                    'The duration of the training was appropriate.',
                    'The pace of the training was suitable for the content.',
                    'The group activities enhanced my understanding of leadership concepts.',
                    'The technology used in the training worked well.'
                ]
            ],
            [
                'title' => 'Practical Application',
                'items' => [
                    'I can apply the leadership skills learned to my current role.',
                    'The case studies were relevant to real-world leadership challenges.',
                    'The training provided actionable strategies I can implement immediately.',
                    'I feel more confident in my leadership abilities after this training.',
                    'The training has improved my ability to manage team conflicts.'
                ]
            ],
            [
                'title' => 'Overall Assessment',
                'items' => [
                    'This training met my professional development needs.',
                    'I would recommend this training to colleagues.',
                    'The training was worth the time invested.',
                    'The training has motivated me to further develop my leadership skills.',
                    'Overall, I am satisfied with the quality of this training program.'
                ]
            ]
        ];

        $this->createCategoriesAndItems($form, $categories);
    }

    /**
     * Create a technical training evaluation form
     */
    private function createTechnicalTrainingEvaluationForm(TrainingCourse $course): void
    {
        // Create the main evaluation form
        $form = EvaluationForm::create([
            'employee_training_course_id' => $course->id,
            'title' => 'Technical Skills Training Evaluation',
            'is_active' => true,
        ]);

        // Create categories and their items
        $categories = [
            [
                'title' => 'Course Content',
                'items' => [
                    'The technical concepts were explained at an appropriate level of detail.',
                    'The course covered all the essential topics I expected.',
                    'The technical documentation provided was comprehensive and useful.',
                    'The course content was up-to-date with current industry standards.',
                    'The technical examples used were relevant to my work.'
                ]
            ],
            [
                'title' => 'Hands-on Practice',
                'items' => [
                    'Sufficient time was allocated for hands-on practice.',
                    'The practical exercises reinforced the theoretical concepts.',
                    'The lab environment was properly set up for the exercises.',
                    'I was able to complete the practical exercises successfully.',
                    'The exercises increased my confidence in applying the technical skills.'
                ]
            ],
            [
                'title' => 'Instructor Expertise',
                'items' => [
                    'The instructor demonstrated expert knowledge of the technical subject.',
                    'The instructor effectively answered complex technical questions.',
                    'The instructor provided helpful troubleshooting guidance.',
                    'The instructor explained difficult concepts in an understandable way.',
                    'The instructor was aware of common challenges and addressed them proactively.'
                ]
            ],
            [
                'title' => 'Learning Resources',
                'items' => [
                    'The provided code samples were helpful and well-documented.',
                    'The reference materials will be useful after the training.',
                    'The online resources recommended are relevant and accessible.',
                    'The training materials included useful diagrams and visual aids.',
                    'The supplementary resources enhanced my understanding of the topics.'
                ]
            ],
            [
                'title' => 'Practical Application',
                'items' => [
                    'I can immediately apply the technical skills learned in my role.',
                    'The training addressed real-world technical challenges I face.',
                    'The course provided strategies for continued learning in this technical area.',
                    'I feel more confident in my technical abilities after this training.',
                    'The training has improved my problem-solving capabilities.'
                ]
            ]
        ];

        $this->createCategoriesAndItems($form, $categories);
    }

    /**
     * Create an onboarding evaluation form
     */
    private function createOnboardingEvaluationForm(TrainingCourse $course): void
    {
        // Create the main evaluation form
        $form = EvaluationForm::create([
            'employee_training_course_id' => $course->id,
            'title' => 'New Employee Onboarding Program Evaluation',
            'is_active' => true,
        ]);

        // Create categories and their items
        $categories = [
            [
                'title' => 'Pre-Boarding Experience',
                'items' => [
                    'I received clear information before my start date.',
                    'The pre-boarding communications made me feel welcome.',
                    'I understood what to expect on my first day.',
                    'The paperwork process was efficient and well-explained.',
                    'I had access to necessary resources before my start date.'
                ]
            ],
            [
                'title' => 'First Week Experience',
                'items' => [
                    'I was properly introduced to my team and key stakeholders.',
                    'My workspace was ready and properly equipped on my first day.',
                    'The orientation schedule was well-organized and informative.',
                    'I received the necessary access to systems and tools promptly.',
                    'I felt welcomed and supported during my first week.'
                ]
            ],
            [
                'title' => 'Company Information',
                'items' => [
                    'The company mission, vision, and values were clearly explained.',
                    'I gained a good understanding of the company structure and departments.',
                    'The company policies and procedures were communicated effectively.',
                    'I understand how my role contributes to the company\'s goals.',
                    'The company culture was accurately represented during onboarding.'
                ]
            ],
            [
                'title' => 'Role-Specific Training',
                'items' => [
                    'I received adequate training specific to my job responsibilities.',
                    'My job expectations and performance metrics were clearly defined.',
                    'I was introduced to the tools and resources needed for my role.',
                    'I had opportunities to ask questions about my role and responsibilities.',
                    'The training provided prepared me to perform my job effectively.'
                ]
            ],
            [
                'title' => 'Manager and Buddy Support',
                'items' => [
                    'My manager was available and supportive during my onboarding period.',
                    'Regular check-ins were scheduled with my manager.',
                    'My assigned buddy/mentor was helpful in my transition.',
                    'I knew who to approach with different types of questions.',
                    'I received constructive feedback during my onboarding period.'
                ]
            ]
        ];

        $this->createCategoriesAndItems($form, $categories);
    }

        /**
     * Create an onboarding evaluation form
     */
    private function createStandardEvaluationForm(TrainingCourse $course): void
    {
        // Create the main evaluation form
        $form = EvaluationForm::create([
            'employee_training_course_id' => $course->id,
            'title' => 'Standard Evaluation Form',
            'is_active' => true,
        ]);

        // Create categories and their items
        $categories = [
            [
                'title' => 'Content and Delivery',
                'items' => [
                    'The content of the seminar was relevant and informative.',
                    'The speaker was knowledgeable and engaging.',
                    'The pace of the presentation was appropriate.',
                    'The materials provided were helpful and useful.',
                    'The examples and case studies were relevant and illustrative.'
                ]
            ],
            [
                'title' => 'Organization and Structure',
                'items' => [
                    'The seminar was well-organized and structured.',
                    'The objectives of the seminar were clearly stated and achieved.',
                    'There was sufficient time for Q&A and discussion.',
                    'The breaks were timed appropriately..',
                ]
            ],
            [
                'title' => 'Overall Satisfaction',
                'items' => [
                    'I found the seminar to be valuable and beneficial.',
                    'I would recommend this seminar to others',
                    'I would be interested in attending future seminars on this topic.',
                ]
            ]
        ];

        $this->createCategoriesAndItems($form, $categories);
    }

    /**
     * Helper method to create categories and items for a form
     */
    private function createCategoriesAndItems(EvaluationForm $form, array $categories): void
    {
        foreach ($categories as $index => $categoryData) {
            $category = EvaluationCategory::create([
                'evaluation_form_id' => $form->id,
                'title' => $categoryData['title'],
                'sequence_order' => $index + 1,
            ]);

            foreach ($categoryData['items'] as $itemIndex => $question) {
                EvaluationItem::create([
                    'evaluation_category_id' => $category->id,
                    'question' => $question,
                    'sequence_order' => $itemIndex + 1,
                ]);
            }
        }
    }
}
