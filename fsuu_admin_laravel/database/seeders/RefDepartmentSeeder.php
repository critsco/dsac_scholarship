<?php

namespace Database\Seeders;

use App\Models\RefDepartment;
use Illuminate\Database\Seeder;

class RefDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            [
                "department_name" => "CSP - Computer Studies Program",
                "courses" => [
                    [
                        "course_name" => "Bachelor of Science in Information Technology"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Computer Science"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Information Technology - Major in Computer Animation"
                    ],
                    [
                        "course_name" => "Bachelor of Library and Information Science"
                    ],
                    [
                        "course_name" => "Computer Programming NC IV"
                    ],
                    [
                        "course_name" => "Computer Hardware Servicing NC II"
                    ],
                ]
            ],
            [
                "department_name" => "ASP - Arts and Sciences Program",
                "courses" => [
                    [
                        "course_name" => "Bachelor of Arts in Economics"
                    ],
                    [
                        "course_name" => "Bachelor of Arts in Filipino Language"
                    ],
                    [
                        "course_name" => "Bachelor of Arts in Applied Mathematics"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Biology"
                    ],
                    [
                        "course_name" => "Bachelor Of Arts"
                    ],
                    [
                        "course_name" => "Bachelor of Arts in English Language"
                    ],
                    [
                        "course_name" => "Bachelor of Arts - Major in Political Science"
                    ],
                    [
                        "course_name" => "Bachelor of Arts - Major in Communication"
                    ],
                    [
                        "course_name" => "Bachelor in Human Services"
                    ],
                    [
                        "course_name" => "Bachelor of Arts in English Language Studies"
                    ],
                    [
                        "course_name" => "Bachelor of Arts - Major in English Language"
                    ],
                    [
                        "course_name" => "Bachelor of Arts in Guidance and Counseling"
                    ],
                    [
                        "course_name" => "Batsilyer ng Sining sa Filipino"
                    ],
                    [
                        "course_name" => "Bachelor of Arts in Human Service"
                    ],
                    [
                        "course_name" => "Bachelof of Science in Psychology"
                    ],
                ]
            ],
            [
                "department_name" => "AP - Accountancy Program",
                "courses" => [
                    [
                        "course_name" => "Bachelor of Science in Accounting Information System"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Internal Audit"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Managerial Accounting"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Management Accounting"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Accountancy"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Accounting Technology"
                    ],
                ]
            ],
            [
                "department_name" => "BAP - Business Administration Program",
                "courses" => [
                    [
                        "course_name" => "Bachelor of Science in Office Administration"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Office Administration-With Specialization in Legal Office Management"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Hotel and Restaurant Management"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Business Administration-Major in Human Resource Development Management"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Hospitality Management"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Business Administration-Major in Operations Management"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Business Administration-Major in Marketing Management"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Business Administration-Major in Human Resource Management"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Social Entrepreneurship-With Specialization in Agri-Aqua Business"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Social Entrepreneurship-With Specialization in Arts and Crafts Business"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Business Administration-Major in Financial Management"
                    ],
                ]
            ],
            [
                "department_name" => "CP - Criminology Program",
                "courses" => [
                    [
                        "course_name" => "Bachelor of Science in Criminology"
                    ]
                ]
            ],
            [
                "department_name" => "ETP - Engineering and Technology Program",
                "courses" => [
                    [
                        "course_name" => "Bachelor of Science in Industrial Engineering"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Civil Engineering"
                    ]
                ]
            ],
            [
                "department_name" => "TEP - Teachers Education Program",
                "courses" => [
                    [
                        "course_name" => "Bachelor of Science in Applied Mathematics"
                    ],
                    [
                        "course_name" => "Bachelor of Special Needs Education"
                    ],
                    [
                        "course_name" => "Bachelor in Secondary Education Major in Physical Science"
                    ],
                    [
                        "course_name" => "Bachelor of Secondary Education - Major in Filipino"
                    ],
                    [
                        "course_name" => "Bachelor of Secondary Education - Major in English"
                    ],
                    [
                        "course_name" => "Bachelor of Technical Teacher Education Major in Drafting Technology"
                    ],
                    [
                        "course_name" => "Bachelor of Secondary Education - Major in Social Studies"
                    ],
                    [
                        "course_name" => "Bachelor of Secondary Education - Major in Science"
                    ],
                    [
                        "course_name" => "Bachelor in Secondary Education Major in MAPEH"
                    ],
                    [
                        "course_name" => "Bachelor of Elementary Education"
                    ],
                    [
                        "course_name" => "Bachelor in Elementary Education Major in Special Education"
                    ],
                    [
                        "course_name" => "Bachelor of Physical Education"
                    ],
                    [
                        "course_name" => "Bachelor of Secondary Education - Major in Mathematics"
                    ],
                    [
                        "course_name" => "Bachelor of Early Childhood Education"
                    ],
                    [
                        "course_name" => "Bachelor of Science in Physical Education"
                    ],
                    [
                        "course_name" => "Bachelor of Technical Teacher Education Major in Food and Service Management"
                    ],
                ]
            ],
            [
                "department_name" => "NP - Nursing Program",
                "courses" => [
                    [
                        "course_name" => "Bachelor of Science in Nursing"
                    ]
                ]
            ]
        ];

        \App\Models\RefDepartment::truncate();
        \App\Models\RefCourse::truncate();

        foreach ($departments as $department) {
            $departmentCreated = \App\Models\RefDepartment::create([
                "department_name" => $department["department_name"],
                "created_by" => 1,
            ]);

            foreach ($department["courses"] as $course) {
                \App\Models\RefCourse::create([
                    "department_id" => $departmentCreated->id,
                    "course_name" => $course["course_name"],
                    "created_by" => 1,
                ]);
            }
        }
    }
}
