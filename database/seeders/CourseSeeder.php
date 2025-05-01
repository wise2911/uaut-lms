<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            // COBA (Business) Courses
            [
                'title' => 'Digital Marketing Fundamentals',
                'description' => 'Master the core concepts of digital marketing and social media strategies',
                'department' => 'COBA',
                'video_url' => 'https://www.youtube.com/embed/7vssQq4Bv4Q', // HubSpot Academy
                'instructor_name' => 'Prof. Sarah Johnson',
                'learning_outcomes' => json_encode([
                    'Develop digital marketing strategies',
                    'Understand SEO and content marketing',
                    'Create effective social media campaigns',
                    'Analyze marketing metrics'
                ]),
                'topics' => json_encode([
                    [
                        'title' => 'Introduction to Digital Marketing',
                        'duration' => '45 min',
                        'lessons' => [
                            'Digital Marketing Landscape',
                            'Customer Journey Mapping',
                            'Marketing Funnels'
                        ]
                    ],
                    [
                        'title' => 'Social Media Marketing',
                        'duration' => '1.5 hours',
                        'lessons' => [
                            'Facebook/Instagram Strategies',
                            'LinkedIn for B2B',
                            'Content Calendar Creation'
                        ]
                    ]
                ]),
                'thumbnail_url' => 'https://i.ytimg.com/vi/7vssQq4Bv4Q/maxresdefault.jpg'
            ],
            [
                'title' => 'Financial Accounting Principles',
                'description' => 'Learn the fundamentals of financial statements and accounting standards',
                'department' => 'COBA',
                'video_url' => 'https://www.youtube.com/embed/8RjrrC6E7D0', // Accounting Stuff
                'instructor_name' => 'Dr. Michael Chen',
                'learning_outcomes' => json_encode([
                    'Prepare financial statements',
                    'Understand GAAP principles',
                    'Analyze balance sheets',
                    'Perform ratio analysis'
                ]),
                'topics' => json_encode([
                    [
                        'title' => 'Accounting Basics',
                        'duration' => '1 hour',
                        'lessons' => [
                            'Double-Entry System',
                            'Chart of Accounts',
                            'T-Accounts and Ledgers'
                        ]
                    ],
                    [
                        'title' => 'Financial Statements',
                        'duration' => '2 hours',
                        'lessons' => [
                            'Balance Sheets',
                            'Income Statements',
                            'Cash Flow Statements'
                        ]
                    ]
                ]),
                'thumbnail_url' => 'https://i.ytimg.com/vi/8RjrrC6E7D0/maxresdefault.jpg'
            ],

            // COEIT (IT) Courses
            [
                'title' => 'Python Programming for Beginners',
                'description' => 'Learn Python from scratch with hands-on exercises and projects',
                'department' => 'COEIT',
                'video_url' => 'https://www.youtube.com/embed/rfscVS0vtbw', // freeCodeCamp
                'instructor_name' => 'Dr. Alan Turing',
                'learning_outcomes' => json_encode([
                    'Write Python programs',
                    'Understand data structures',
                    'Build simple applications',
                    'Debug Python code'
                ]),
                'topics' => json_encode([
                    [
                        'title' => 'Python Basics',
                        'duration' => '2 hours',
                        'lessons' => [
                            'Variables and Data Types',
                            'Conditional Statements',
                            'Loops and Iterations'
                        ]
                    ],
                    [
                        'title' => 'Intermediate Python',
                        'duration' => '3 hours',
                        'lessons' => [
                            'Functions and Modules',
                            'File Handling',
                            'Error Handling'
                        ]
                    ]
                ]),
                'thumbnail_url' => 'https://i.ytimg.com/vi/rfscVS0vtbw/maxresdefault.jpg'
            ],
            [
                'title' => 'Cybersecurity Essentials',
                'description' => 'Protect systems and networks from digital attacks',
                'department' => 'COEIT',
                'video_url' => 'https://www.youtube.com/embed/inWWhr5tnEA', // IBM Technology
                'instructor_name' => 'Prof. Grace Hopper',
                'learning_outcomes' => json_encode([
                    'Identify security threats',
                    'Implement security measures',
                    'Understand encryption',
                    'Secure networks'
                ]),
                'topics' => json_encode([
                    [
                        'title' => 'Security Fundamentals',
                        'duration' => '1.5 hours',
                        'lessons' => [
                            'Threat Landscape',
                            'Security Principles',
                            'Risk Management'
                        ]
                    ],
                    [
                        'title' => 'Practical Security',
                        'duration' => '2 hours',
                        'lessons' => [
                            'Firewalls and VPNs',
                            'Password Security',
                            'Social Engineering'
                        ]
                    ]
                ]),
                'thumbnail_url' => 'https://i.ytimg.com/vi/inWWhr5tnEA/maxresdefault.jpg'
            ],
            [
                'title' => 'Web Development with HTML/CSS',
                'description' => 'Build responsive websites from scratch',
                'department' => 'COEIT',
                'video_url' => 'https://www.youtube.com/embed/mU6anWqZJcc', // SuperSimpleDev
                'instructor_name' => 'Dr. Tim Berners-Lee',
                'learning_outcomes' => json_encode([
                    'Create web pages with HTML',
                    'Style with CSS',
                    'Build responsive layouts',
                    'Implement web design principles'
                ]),
                'topics' => json_encode([
                    [
                        'title' => 'HTML Foundations',
                        'duration' => '1 hour',
                        'lessons' => [
                            'HTML Document Structure',
                            'Forms and Inputs',
                            'Semantic HTML'
                        ]
                    ],
                    [
                        'title' => 'CSS Mastery',
                        'duration' => '2 hours',
                        'lessons' => [
                            'CSS Selectors',
                            'Flexbox and Grid',
                            'Media Queries'
                        ]
                    ]
                ]),
                'thumbnail_url' => 'https://i.ytimg.com/vi/mU6anWqZJcc/maxresdefault.jpg'
            ]
        ];

        foreach ($courses as $courseData) {
            Course::create($courseData);
        }
    }
}