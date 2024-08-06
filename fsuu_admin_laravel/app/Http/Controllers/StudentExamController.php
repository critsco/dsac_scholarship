<?php

namespace App\Http\Controllers;

use App\Imports\ExamResultImport;
use App\Models\Attachment;
use App\Models\Profile;
use App\Models\RefCourse;
use App\Models\RefDepartment;
use App\Models\RefExamCategory;
use App\Models\RefExamSchedule;
use App\Models\RefSemester;
use App\Models\StudentAcademic;
use App\Models\StudentExam;
use App\Models\StudentExamResult;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class StudentExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fullname = "SELECT (SELECT CONCAT(lastname, ', ', firstname, CASE WHEN middlename IS NOT NULL AND middlename <> '' THEN CONCAT(' ', LEFT(middlename, 1), '.') ELSE '' END) FROM `profiles` WHERE `profiles`.id = student_academics.profile_id) FROM student_academics WHERE student_academics.id = student_exams.student_academic_id LIMIT 1";

        $user_id = "SELECT (SELECT user_id FROM `profiles` WHERE `profiles`.id = student_academics.profile_id) FROM student_academics WHERE student_academics.id = student_exams.student_academic_id LIMIT 1";
        $email = "SELECT (SELECT (SELECT email FROM users WHERE users.id = `profiles`.user_id) FROM `profiles` WHERE `profiles`.id = student_academics.profile_id) FROM student_academics WHERE student_academics.id = student_exams.student_academic_id LIMIT 1";
        $personal_email = "SELECT (SELECT email FROM profile_contact_informations WHERE profile_contact_informations.profile_id = student_academics.profile_id LIMIT 1) from student_academics WHERE student_academics.id = student_exams.student_academic_id LIMIT 1";

        $student_status = "SELECT student_status FROM student_academics WHERE student_academics.id = student_exams.student_academic_id LIMIT 1";
        $student_level = "SELECT (SELECT school_level FROM ref_school_levels WHERE ref_school_levels.id = student_academics.student_level_id) FROM student_academics WHERE student_academics.id = student_exams.student_academic_id LIMIT 1";
        $category = "SELECT category FROM student_exams WHERE student_exams.id = student_exams.id LIMIT 1";

        $exam_date = "SELECT exam_date FROM ref_exam_schedules WHERE ref_exam_schedules.id=student_exams.exam_schedule_id LIMIT 1";
        $exam_time = "SELECT CONCAT(time_in, ' ',time_in_meridiem, ' - ', time_out , ' ', time_out_meridiem) FROM ref_exam_schedules WHERE ref_exam_schedules.id = student_exams.exam_schedule_id LIMIT 1";

        $total_available_slot = "(SELECT (slots - (SELECT COUNT(*) FROM student_exams  WHERE ref_exam_schedules.id = student_exams.exam_schedule_id AND schedule_status = 'Approved' LIMIT 1)) FROM ref_exam_schedules WHERE ref_exam_schedules.id = student_exams.exam_schedule_id  LIMIT 1) ";
        $school_year = "SELECT CONCAT(sy_from,'-',sy_to) FROM ref_exam_schedules WHERE ref_exam_schedules.id = student_exams.exam_schedule_id LIMIT 1";
        $semester = "SELECT (SELECT semester FROM ref_semesters WHERE ref_semesters.id = ref_exam_schedules.semester_id) FROM ref_exam_schedules WHERE ref_exam_schedules.id = student_exams.exam_schedule_id LIMIT 1";

        $home_address = "SELECT (SELECT (SELECT (SELECT municipality FROM ref_municipalities WHERE ref_municipalities.id = profile_addresses.city_id LIMIT 1) FROM profile_addresses WHERE profile_addresses.profile_id = `profiles`.id AND profile_addresses.is_home_address = 1 ORDER BY profile_addresses.id DESC LIMIT 1) FROM `profiles` WHERE `profiles`.id = student_academics.profile_id) FROM student_academics WHERE student_academics.id = student_exams.student_academic_id";
        $current_address = "SELECT(SELECT (SELECT (SELECT municipality FROM ref_municipalities WHERE ref_municipalities.id = profile_addresses.city_id LIMIT 1) FROM profile_addresses WHERE profile_addresses.profile_id = `profiles`.id AND profile_addresses.is_current_address = 1 ORDER BY profile_addresses.id DESC LIMIT 1) FROM `profiles` WHERE `profiles`.id = student_academics.profile_id) FROM student_academics WHERE student_academics.id = student_exams.student_academic_id";

        $data = StudentExam::select([
            '*',
            DB::raw("($fullname) fullname"),

            DB::raw("($user_id) user_id"),
            DB::raw("($email) email"),
            DB::raw("($personal_email) personal_email"),

            DB::raw("($student_status) student_status"),
            DB::raw("($student_level) student_level"),
            DB::raw("($category) category"),

            DB::raw("($exam_date) exam_date"),
            DB::raw("($exam_time) exam_time"),

            DB::raw("$total_available_slot total_available_slot"),
            DB::raw("($school_year) school_year"),
            DB::raw("($semester) semester"),

            DB::raw("($home_address) home_address"),
            DB::raw("($current_address) current_address"),
        ])
            ->with([
                'student_academic' => function ($query) {
                    $query->with([
                        'profile' => function ($query) {
                            $query->with([
                                'attachments',
                                'profile_addresses' => function ($query) {
                                    $query->where('category', 'STUDENT ADDRESS');
                                },
                                'profile_contact_informations' => function ($query) {
                                    $query->where('category', 'Student Contact Information');
                                },
                                'user'
                            ]);
                        },
                        'current_course' => function ($query) {
                            $query->with(['ref_department']);
                        },
                        'first_course' => function ($query) {
                            $query->with(['ref_department']);
                        },
                        'second_course' => function ($query) {
                            $query->with(['ref_department']);
                        },
                        'third_course' => function ($query) {
                            $query->with(['ref_department']);
                        },
                        'ref_school_level',
                        'ref_scholarship',
                    ]);
                },
                'ref_exam_schedules',
                'ref_exam_categories',
                'student_exam_results',
            ]);

        if ($request->has("exam_schedule_id")) {
            $data->where("exam_schedule_id", $request->exam_schedule_id);
        }

        // applicants walk-in, online, & archived
        if ($request->from === "walk-in") {
            $data->where('category', 'Walk-In');

            if ($request->has('tabActive')) {
                if ($request->tabActive == "Approval Schedule List View") {
                    $data->where('schedule_status', 'Applied')->whereDate('updated_at', '>=', now()->subDays(3)->toDateString())->get();
                } else if ($request->tabActive == "Exam Status List View") {
                    $data->where('exam_status', $request->exam_status)
                        ->where('schedule_status', 'Approved');
                } else if ($request->tabActive == "Exam Result List View") {
                    $category = [];

                    if ($request->category) {
                        $category = explode(',', $request->category);
                    }

                    $examCategories = RefExamCategory::whereIn('category', $category)->get();

                    $data->where('exam_status', "Checked")
                        ->where('exam_result', "Available")
                        ->whereHas('ref_exam_categories', function ($query) use ($examCategories) {
                            $query->whereIn('id', $examCategories->pluck('id')->toArray());
                        });
                }
            }

            $data->where(function ($query) use ($request, $email, $student_status, $student_level) {
                if ($request->search) {
                    $query->orWhere(DB::raw("($email)"), 'LIKE', "%$request->search%");
                    $query->orWhere(DB::raw("($student_status)"), 'LIKE', "%$request->search%");
                    $query->orWhere(DB::raw("($student_level)"), 'LIKE', "%$request->search%");
                }
            });
        } else if ($request->from === "online") {
            $data->where('category', 'Online');

            if ($request->tabActive) {
                if ($request->tabActive == "Approval Schedule List View") {
                    $data->where('schedule_status', 'Applied')->whereDate('updated_at', '>=', now()->subDays(3)->toDateString())->get();
                } else if ($request->tabActive == "Exam Result List View") {
                    $data->where('exam_status', "Checked")->where('exam_result', "Available");
                } else if ($request->tabActive == "Email Result List View") {
                    $data->where('exam_status', "Checked");
                }
            }

            $data->where(function ($query) use ($request, $email, $student_status, $student_level) {
                if ($request->search) {
                    $query->orWhere(DB::raw("($email)"), 'LIKE', "%$request->search%");
                    $query->orWhere(DB::raw("($student_status)"), 'LIKE', "%$request->search%");
                    $query->orWhere(DB::raw("($student_level)"), 'LIKE', "%$request->search%");
                }
            });
        }

        if ($request->has('status')) {

            if ($request->status == 'Deactivated') {
                $data->onlyTrashed();
            }
        }

        if ($request->sort_field && $request->sort_order) {
            if (
                $request->sort_field != '' && $request->sort_field != 'undefined' && $request->sort_field != 'null' &&
                $request->sort_order != '' && $request->sort_order != 'undefined' && $request->sort_order != 'null'
            ) {
                $data = $data->orderBy(isset($request->sort_field) ? $request->sort_field : 'id', isset($request->sort_order) ?
                    $request->sort_order : 'desc');
            }
        } else {
            $data = $data->orderBy('id', 'desc');
        }

        if ($request->page_size) {
            $data = $data->limit($request->page_size)
                ->paginate($request->page_size, ['*'], 'page', $request->page)
                ->toArray();

            // $data['data'] = collect($data['data'])->map(function ($value) {
            //     $value['profile']['attachments'] = collect($value['profile']['attachments'])->map(function ($value) {
            //         if ($value['file_type'] == 'document') {
            //             $value['pdf'] = "data:application/pdf;base64," . base64_encode(file_get_contents($value['file_path']));
            //         } else {
            //             $value['pdf'] = null;
            //         }
            //         return $value;
            //     });

            //     return $value;
            // });
        } else {
            $data = $data->get();
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'from' => $request->from,
            'request' => $request->all(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not created",
        ];

        DB::transaction(function () use ($request, &$ret) {
            $request->validate([
                'email' => [
                    'required',
                    Rule::unique('users')->ignore($request->id),
                ],
                'username' => [
                    'required',
                    Rule::unique('users')->ignore($request->id),
                ],
                'password' => 'required',

                'firstname' => 'required',
                'middlename' => 'sometimes|required',
                'lastname' => 'required',

                'birthplace' => 'required',
                'birthdate' => 'required',
                'age' => 'required',

                'contact_number' => 'required',
                'email' => 'required',

                'address_list' => 'required',

                'have_disability' => 'required',
                'have_difficulty' => 'required',

                'student_level_id' => 'required',
                'student_strand' => 'sometimes|required',
                'current_course_id' => 'sometimes|required',

                'exam_schedule_id' => 'required',
                'exam_category_id' => 'required',
                'student_status' => 'required',

                'first_course_id' => 'sometimes|required',
                'second_course_id' => 'sometimes|required',
                'third_course_id' => 'sometimes|required',

                'previous_school_name' => 'sometimes|required',
                'previous_school_year' => 'sometimes|required',

                'data_consent' => 'required',
            ]);

            // Create & Update User
            $createdUser = [
                "user_role_id" => 4,
                "username" => $request->username,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "created_by" => auth()->user()->id,
                "status" => 'Active',
            ];

            $users = User::where('email', $request->email)->first();
            if ($users) {
                $updateusers = $users->fill($createdUser);
                $updateusers->save();
            } else {
                $users = \App\Models\User::create($createdUser);
            }

            $dataProfile = [
                "user_id" => $users->id,
                "school_id" => $request->school_id,

                "firstname" => $request->firstname,
                "middlename" => $request->middlename,
                "lastname" => $request->lastname,
                "name_ext" => $request->name_ext,
                "birthplace" => $request->birthplace,
                "birthdate" => $request->birthdate,
                "age" => $request->age,
                "gender" => $request->gender ?? null,
                "religion_id" => $request->religion_id,
                "civil_status_id" => $request->civil_status_id,
                "nationality_id" => $request->nationality_id,
                "blood_type" => $request->blood_type ?? null,
                "height" => $request->height ?? null,
                "weight" => $request->weight ?? null,

                'data_consent' => is_array($request->data_consent) ? implode(', ', $request->data_consent) : $request->data_consent,
            ];

            $findProfilByUserId = \App\Models\Profile::where('user_id', $users->id)->first();

            $folder_name = "";

            //School ID
            if ($findProfilByUserId) {
                if ($request->student_type === null) {
                    $dataProfile["school_id"] = $this->generate_school_id("", $request->student_type);
                }
                if ($findProfilByUserId->folder_name) {
                    $folder_name = $findProfilByUserId->folder_name;
                } else {
                    $folder_name = Str::random(10);
                    $dataProfile["folder_name"] = $folder_name;
                }
            } else {
                if ($request->student_type === null) {
                    $dataProfile["school_id"] = $this->generate_school_id("", $request->student_type);
                }
                $folder_name = Str::random(10);
                $dataProfile["folder_name"] = $folder_name;
            }

            $profile_id = "";

            // Profile Picture
            if ($findProfilByUserId) {
                $profile_id = $findProfilByUserId->id;
                $dataProfile["updated_by"] = auth()->user()->id;

                $findProfilByUserIdUpdate = $findProfilByUserId->fill($dataProfile);
                $findProfilByUserIdUpdate->save();

                if ($request->hasFile('profile_picture')) {
                    $this->create_attachment($findProfilByUserId, $request->file('profile_picture'), [
                        "action" => "Add",
                        // "folder_name" => "profiles/profile-$profile_id/profile_pictures",
                        "folder_name" => "Profile Picture",
                        "file_description" => "Applicant Profile",
                        "file_type" => "image",
                    ]);
                }
            } else {
                $dataProfile["created_by"] = auth()->user()->id;
                $createProfile = \App\Models\Profile::create($dataProfile);

                if ($createProfile) {
                    $profile_id = $createProfile->id;

                    if ($request->hasFile('profile_picture')) {
                        $this->create_attachment($createProfile, $request->file('profile_picture'), [
                            "action" => "Add",
                            "file_name" => "Profile Picture",
                            "file_description" => "Applicant Profile",
                            "folder_name" => "profiles/profile-$profile_id/profile_pictures",
                            "file_type" => "image",
                        ]);
                    }
                }
            }

            $contact_number = $request->contact_number;
            $address_list = $request->address_list;

            if ($profile_id != "") {

                // Language Update and Create
                $languages = is_array($request->language) ? $request->language : [$request->language];

                $existingLanguages = \App\Models\ProfileLanguage::where('profile_id', $profile_id)->pluck('language')->toArray();

                foreach ($existingLanguages as $current) {
                    if (!in_array($current, $languages)) {
                        // Deactive existing language if not in new languages
                        \App\Models\ProfileLanguage::where('profile_id', $profile_id)
                            ->where('language', $current)
                            ->update(['status' => 0]);
                    }
                }

                // Add new languages
                foreach ($languages as $language) {
                    // Check if language is not empty or null
                    if (!empty($language)) {
                        $findLanguage = \App\Models\ProfileLanguage::where('profile_id', $profile_id)
                            ->where('language', $language)
                            ->first();

                        if ($findLanguage) {
                            $findLanguage->fill([
                                "profile_id" => $profile_id,
                                'language' => $language,
                                'status' => 1,
                                'updated_by' => auth()->user()->id
                            ])->save();
                        } else {
                            \App\Models\ProfileLanguage::create([
                                "profile_id" => $profile_id,
                                'language' => $language,
                                'status' => 1,
                                'created_by' => auth()->user()->id
                            ]);
                        }
                    }
                }

                // Contact Information Update and Create
                if ($contact_number != "") {

                    \App\Models\ProfileContactInformation::where("profile_id", $profile_id)->update(['status' => 0]);

                    $findContactInformation = \App\Models\ProfileContactInformation::where("contact_number", $contact_number)
                        ->where("profile_id", $profile_id)
                        ->first();

                    if ($findContactInformation) {
                        $findContactInformation->fill([
                            "status" => 1,
                            "email" => $request->personal_email,
                            "updated_by" => auth()->user()->id,
                        ])->save();
                    } else {
                        \App\Models\ProfileContactInformation::create([
                            'contact_number' => $contact_number,
                            'category' => 'Student Contact Information',
                            "fullname" => $request->firstname . ' ' . (!empty($request->middlename) ? $request->middlename . ' ' : '') .
                                $request->lastname,
                            "email" => $request->personal_email,
                            "profile_id" => $profile_id,
                            "created_by" => auth()->user()->id,
                            'status' => 1,
                        ]);
                    }
                }

                // Student Address Update and Create
                if (!empty($address_list)) {
                    foreach ($address_list as $key => $value) {
                        if (!empty($value['id'])) {
                            $findStudentAddress = \App\Models\ProfileAddress::where('id', $value['id'])
                                ->where('category', 'STUDENT ADDRESS')
                                ->first();

                            if ($findStudentAddress) {
                                $findStudentAddress->fill([
                                    "profile_id" => $profile_id,
                                    'category' => "STUDENT ADDRESS",
                                    'address' => $value['address'] ?? null,
                                    'city_id' => $value['municipality_id'] ?? null,
                                    'barangay_id' => $value['barangay_id'] ?? null,
                                    'is_home_address' => !empty($value['is_home_address']) && $value['is_home_address'] ? 1 : 0,
                                    'is_current_address' => !empty($value['is_current_address']) && $value['is_current_address'] ? 1 : 0,
                                    'updated_by' => auth()->user()->id
                                ])->save();
                            }
                        } else {
                            \App\Models\ProfileAddress::create([
                                "profile_id" => $profile_id,
                                'category' => "STUDENT ADDRESS",
                                'address' => $value['address'] ?? null,
                                'city_id' => $value['municipality_id'] ?? null,
                                'barangay_id' => $value['barangay_id'] ?? null,
                                'is_home_address' => !empty($value['is_home_address']) && $value['is_home_address'] ? 1 : 0,
                                'is_current_address' => !empty($value['is_current_address']) && $value['is_current_address'] ? 1 : 0,
                                'created_by' => auth()->user()->id
                            ]);
                        }
                    }
                }

                // Profile Health Information
                $findHealthInfo = \App\Models\ProfileHealthInformations::where('profile_id', $profile_id)
                    ->first();

                if ($findHealthInfo) {
                    $findHealthInfo->fill([
                        "profile_id" => $profile_id,

                        'have_disability' => $request->have_disability ?? null,
                        'disability_type' => is_array($request->disability_type) ? implode(', ', $request->disability_type) :
                            $request->disability_type,
                        'other_disability' => $request->other_disability ?? null,

                        'have_difficulty' => $request->have_difficulty ?? null,
                        'difficulty_type' => is_array($request->difficulty_type) ? implode(', ', $request->difficulty_type) :
                            $request->difficulty_type,
                        'other_difficulty' => $request->other_difficulty ?? null,


                        'updated_by' => auth()->user()->id
                    ])->save();
                } else {
                    \App\Models\ProfileHealthInformations::create([
                        "profile_id" => $profile_id,

                        'have_disability' => $request->have_disability ?? null,
                        'disability_type' => is_array($request->disability_type) ? implode(', ', $request->disability_type) :
                            $request->disability_type,

                        'other_disability' => $request->other_disability ?? null,

                        'have_difficulty' => $request->have_difficulty ?? null,
                        'difficulty_type' => is_array($request->difficulty_type) ? implode(', ', $request->difficulty_type) :
                            $request->difficulty_type,
                        'other_difficulty' => $request->other_difficulty ?? null,

                        'created_by' => auth()->user()->id
                    ]);
                }

                // Academic Profile Update & Create
                $findStudentAcademic = \App\Models\StudentAcademic::where('profile_id', $profile_id)
                    ->where('category', 'Academic Profile')
                    ->first();

                $scholarship_id = $request->scholarship_id ? implode(', ', $request->scholarship_id) : '';

                if ($findStudentAcademic) {
                    $findStudentAcademic->fill([
                        "profile_id" => $profile_id,
                        'student_status' => $request->student_status ?? null,
                        'student_level_id' => $request->student_level_id ?? null,
                        // 'student_strand' => $request->student_level_id == 4 ? $request->student_strand : null,
                        'student_strand' => $request->student_strand,
                        'current_course_id' => $request->current_course_id,

                        // Top three courses
                        'first_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->first_course_id) ? $request->first_course_id : null),
                        'second_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->second_course_id) ? $request->second_course_id : null),
                        'third_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->third_course_id) ? $request->third_course_id : null),

                        // Transferee
                        'previous_school_name' => $request->student_status == "Transferee" ? $request->previous_school_name : null,
                        'previous_school_year' => $request->student_status == "Transferee" ? $request->previous_school_year : null,
                        'applied_to_fsuu' => $request->student_status == "Transferee" ? $request->applied_to_fsuu : null,
                        'year_applied' => $request->student_status == "Transferee" && $request->applied_to_fsuu == "No" ? null :
                            $request->year_applied,
                        'accepted_to_fsuu' => $request->student_status == "Transferee" ? $request->accepted_to_fsuu : null,
                        'year_accepted' => $request->student_status == "Transferee" && $request->accepted_to_fsuu == "No" ? null :
                            $request->year_accepted,
                        'attended_to_fsuu' => $request->student_status == "Transferee" ? $request->attended_to_fsuu : null,
                        'year_attended' => $request->student_status == "Transferee" && $request->attended_to_fsuu == "No" ? null :
                            $request->year_attended,

                        // Scholarships
                        'scholarship_id' =>  $scholarship_id,
                        "apply_scholarship" => $request->apply_scholarship,

                        // Pursuing a Second Degree
                        'intend_to_pursue' => $request->student_status == "Pursuing a Second Degree" ? $request->intend_to_pursue : null,
                        'working_student' => $request->student_status == "Pursuing a Second Degree" ? $request->working_student : null,
                        'employer_name' => $request->student_status == "Pursuing a Second Degree" ? $request->employer_name : null,
                        'employer_address' => $request->student_status == "Pursuing a Second Degree" ? $request->employer_address : null,

                        'updated_by' => auth()->user()->id
                    ])->save();
                } else {
                    $findStudentAcademic =  \App\Models\StudentAcademic::create([
                        "profile_id" => $profile_id,
                        'category' => 'Academic Profile',
                        'student_status' => $request->student_status ?? null,
                        'student_level_id' => $request->student_level_id ?? null,
                        'student_strand' => $request->student_strand ?? null,
                        'current_course_id' => $request->current_course_id ?? null,

                        // Top three courses
                        'first_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->first_course_id) ? $request->first_course_id : null),
                        'second_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->second_course_id) ? $request->second_course_id : null),
                        'third_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->third_course_id) ? $request->third_course_id : null),

                        // Transferee
                        'previous_school_name' => $request->previous_school_name ?? null,
                        'previous_school_year' => $request->previous_school_year ?? null,
                        'applied_to_fsuu' => $request->applied_to_fsuu ?? null,
                        'year_applied' => $request->year_applied ?? null,
                        'accepted_to_fsuu' => $request->accepted_to_fsuu ?? null,
                        'year_accepted' => $request->year_accepted ?? null,
                        'attended_to_fsuu' => $request->attended_to_fsuu ?? null,
                        'year_attended' => $request->year_attended ?? null,

                        // Scholarships
                        'scholarship_id' =>  $scholarship_id,
                        "apply_scholarship" => $request->apply_scholarship,

                        // Pursuing a Second Degree
                        'intend_to_pursue' => $request->intend_to_pursue ?? null,
                        'working_student' => $request->working_student ?? null,
                        'employer_name' => $request->employer_name ?? null,
                        'employer_address' => $request->employer_address ?? null,

                        'created_by' => auth()->user()->id
                    ]);
                }

                // Student Exam Result Update and Create
                $findStudentExam = \App\Models\StudentExam::where("student_academic_id", $findStudentAcademic->id)
                    ->first();

                // $examCategory = RefExamCategory::where('id', $request->exam_category_id)->first();

                if ($findStudentExam) {

                    $findStudentExam->fill([
                        "student_academic_id" => $findStudentAcademic->id,
                        'exam_schedule_id' => $request->exam_schedule_id,
                        "exam_category_id" => $request->exam_category_id,
                        "schedule_status" => $request->schedule_status,
                        "updated_by" => auth()->user()->id,
                    ])->save();
                } else {
                    \App\Models\StudentExam::create([
                        "student_academic_id" => $findStudentAcademic->id,
                        'exam_schedule_id' => $request->exam_schedule_id,
                        "exam_category_id" => $request->exam_category_id,
                        "schedule_status" => 'Applied',
                        "category" => 'Walk-In',
                        "status" => 'Active',
                        "created_by" => auth()->user()->id,
                    ]);
                }

                $this->user_persmissions($users->id, $request->user_role_id);
            }


            // Email ACCOUNT REGISTRATION
            $this->send_email([
                'title' => "ACCOUNT REGISTRATION",
                'to_name' => $request->firstname . " " . $request->lastname,
                'account' => $request->username,
                'password' => $request->password,
                'exam_schedule ' => $request->exam_schedule_id,
                'position' => "FSUU GUIDANCE",
                'to_email' => $request->personal_email,
                'sender_name' => auth()->user()->firstname . " " . auth()->user()->lastname,
                "system_id" => 3,
            ]);

            $this->send_notification([
                "title" => "New Student Application",
                "description" => "Your application was approved",
                "link" => "",
                "link_origin" => $request->link_origin,
                "userIds" => [$users->id],
                "system_id" => 3,
            ]);

            $ret = [
                "success" => true,
                "message" => "Data " . ($request->id ? "updated" : "saved") . " successfully"
            ];
        });

        return response()->json($ret, 200);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\StudentExam $StudentExam
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Profile::with([
            'user',
            'student_academics' => function ($query) {
                $query->with([
                    'student_exams',
                    'current_course.ref_department',
                    'first_course.ref_department',
                    'second_course.ref_department',
                    'third_course.ref_department',
                    'ref_school_level',
                    'ref_scholarship',
                ]);
            },
            'profile_addresses',
            'profile_beneficiaries',
            'profile_contact_informations',
            'profile_insurances',
            'profile_health_informations',
            'profile_languages',
            'profile_parent_informations',
            'profile_school_attendeds',
            'profile_spouses',
        ])
            ->find($id);

        $ret = [
            "success" => true,
            "data" => $data
        ];

        return response()->json($ret, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\StudentExam $StudentExam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentExam $StudentExam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\StudentExam $StudentExam
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findData = StudentExam::find($id);

        if ($findData) {
            $findData->fill([
                "deleted_by" => auth()->user()->id,
                "updated_by" => auth()->user()->id,
                "status" => "Deactivated",
            ])->save();

            if ($findData->delete()) {
                $ret = [
                    "success" => true,
                    "message" => "Data deleted successfully"
                ];
            }
        }

        return response()->json($ret, 200);
    }

    public function update_student_basic_info(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not updated",
        ];

        try {
            $request->validate([
                'auth_password' => 'required',
            ]);

            $error = false;

            // Auth Password for Changes
            $check_password = $this->check_password($request->auth_password);

            if ($check_password) {
                $error = false;
                $ret = [
                    "success" => false,
                    "message" => "Password did not match",
                ];
            }

            if (!$error) {
                $profile_id = $request->profile_id;

                // Update User based on profile_id
                $findUserById = Profile::find($profile_id);

                if (!$findUserById) {
                    $ret = [
                        "success" => false,
                        "message" => "User not found",
                    ];
                }

                if ($findUserById) {
                    $dataProfile = [
                        "firstname" => $request->firstname,
                        "middlename" => $request->middlename ?? null,
                        "lastname" => $request->lastname,
                        "name_ext" => $request->name_ext ?? null,
                        "birthplace" => $request->birthplace,
                        "birthdate" => new DateTime($request->birthdate),
                        "age" => is_numeric($request->age) ? (int)$request->age : null,
                        "gender" => $request->gender ?? null,
                        "religion_id" => is_numeric($request->religion_id) ? (int)$request->religion_id : null,
                        "civil_status_id" => is_numeric($request->civil_status_id) ? (int)$request->civil_status_id : null,
                        "nationality_id" => is_numeric($request->nationality_id) ? (int)$request->nationality_id : null,
                        "blood_type" => $request->blood_type ?? null,
                        "height" => $request->height ?? null,
                        "weight" => $request->weight ?? null,
                    ];

                    $findUserById->fill($dataProfile);
                    $findUserById->save();

                    $profile_id = "";

                    $findProfilByUserId = \App\Models\Profile::where('user_id', $findUserById->id)->first();

                    // Profile Picture
                    if ($findProfilByUserId) {
                        $profile_id = $findProfilByUserId->id;
                        $dataProfile["updated_by"] = auth()->user()->id;

                        $findProfilByUserIdUpdate = $findProfilByUserId->fill($dataProfile);
                        $findProfilByUserIdUpdate->save();

                        if ($request->hasFile('profile_picture')) {
                            $this->create_attachment($findProfilByUserId, $request->file('profile_picture'), [
                                "action" => "Add",
                                "folder_name" => "profiles/profile-$profile_id/profile_pictures",
                                // "folder_name" => "Profile Picture",
                                "file_type" => "image",
                                "file_description" => "Applicant Profile",
                            ]);
                        }
                    } else {
                        $dataProfile["created_by"] = auth()->user()->id;
                        $createProfile = \App\Models\Profile::create($dataProfile);

                        if ($createProfile) {
                            $profile_id = $createProfile->id;

                            if ($request->hasFile('profile_picture')) {
                                $this->create_attachment($createProfile, $request->file('profile_picture'), [
                                    "action" => "Add",
                                    "file_name" => "Profile Picture",
                                    "file_description" => "Applicant Profile",
                                    "folder_name" => "profiles/profile-$profile_id/profile_pictures",
                                    "file_type" => "image",
                                ]);
                            }
                        }
                    }

                    $profile_id = $request->profile_id;
                    $contact_number = $request->contact_number;

                    if ($profile_id != "") {

                        // Language Update and Create
                        $languages = is_array($request->language) ? $request->language : [$request->language];

                        $existingLanguages = \App\Models\ProfileLanguage::where('profile_id', $profile_id)->pluck('language')->toArray();

                        foreach ($existingLanguages as $current) {
                            if (!in_array($current, $languages)) {
                                // Deactive existing language if not in new languages
                                \App\Models\ProfileLanguage::where('profile_id', $profile_id)
                                    ->where('language', $current)
                                    ->update(['status' => 0]);
                            }
                        }

                        // Add new languages
                        foreach ($languages as $language) {
                            $findLanguage = \App\Models\ProfileLanguage::where('profile_id', $profile_id)
                                ->where('language', $language)
                                ->first();

                            if ($findLanguage) {
                                $findLanguage->fill([
                                    "profile_id" => $profile_id,
                                    'language' => $language,
                                    'status' => 1,
                                    'updated_by' => auth()->user()->id
                                ])->save();
                            } else {
                                \App\Models\ProfileLanguage::create([
                                    "profile_id" => $profile_id,
                                    'language' => $language,
                                    'status' => 1,
                                    'created_by' => auth()->user()->id
                                ]);
                            }
                        }

                        // Contact Information Update & Create
                        if ($contact_number != "") {
                            \App\Models\ProfileContactInformation::where("profile_id", $profile_id)->update(['status' => 0]);

                            $findContactInformation = \App\Models\ProfileContactInformation::where("contact_number", $contact_number)
                                ->where("profile_id", $profile_id)
                                ->first();

                            if ($findContactInformation) {
                                $findContactInformation->fill([
                                    "status" => 1,
                                    "email" => $request->personal_email,
                                    "updated_by" => auth()->user()->id,
                                ])->save();
                            } else {
                                \App\Models\ProfileContactInformation::create([
                                    'contact_number' => $contact_number,
                                    'category' => 'Student Contact Information',
                                    "fullname" => $request->firstname . ' ' . (!empty($request->middlename) ? $request->middlename . ' ' : '') .
                                        $request->lastname,
                                    "email" => $request->personal_email,
                                    "profile_id" => $profile_id,
                                    "created_by" => auth()->user()->id,
                                    'status' => 1,
                                ]);
                            }
                        }

                        // Profile Health Information
                        $findHealthInfo = \App\Models\ProfileHealthInformations::where('profile_id', $profile_id)
                            ->first();

                        if ($findHealthInfo) {
                            $findHealthInfo->fill([
                                "profile_id" => $profile_id,

                                'have_disability' => $request->have_disability ?? null,
                                'disability_type' => is_array($request->disability_type) ? implode(', ', $request->disability_type) :
                                    $request->disability_type,
                                'other_disability' => $request->other_disability ?? null,

                                'have_difficulty' => $request->have_difficulty ?? null,
                                'difficulty_type' => is_array($request->difficulty_type) ? implode(', ', $request->difficulty_type) :
                                    $request->difficulty_type,
                                'other_difficulty' => $request->other_difficulty ?? null,


                                'updated_by' => auth()->user()->id
                            ])->save();
                        } else {
                            \App\Models\ProfileHealthInformations::create([
                                "profile_id" => $profile_id,

                                'have_disability' => $request->have_disability ?? null,
                                'disability_type' => is_array($request->disability_type) ? implode(', ', $request->disability_type) :
                                    $request->disability_type,

                                'other_disability' => $request->other_disability ?? null,

                                'have_difficulty' => $request->have_difficulty ?? null,
                                'difficulty_type' => is_array($request->difficulty_type) ? implode(', ', $request->difficulty_type) :
                                    $request->difficulty_type,
                                'other_difficulty' => $request->other_difficulty ?? null,

                                'created_by' => auth()->user()->id
                            ]);
                        }

                        // Academic Profile Update & Create
                        $findStudentAcademic = \App\Models\StudentAcademic::where('profile_id', $profile_id)
                            ->where('category', 'Academic Profile')
                            ->first();

                        $scholarship_id = $request->scholarship_id ? implode(', ', $request->scholarship_id) : '';

                        if ($findStudentAcademic) {
                            $findStudentAcademic->fill([
                                "profile_id" => $profile_id,
                                'student_status' => $request->student_status ?? null,
                                'student_level_id' => $request->student_level_id ?? null,
                                'student_strand' => $request->student_strand,
                                'current_course_id' => $request->current_course_id,

                                // Top three courses
                                'first_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->first_course_id) ? $request->first_course_id : null),
                                'second_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->second_course_id) ? $request->second_course_id : null),
                                'third_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->third_course_id) ? $request->third_course_id : null),

                                // Transferee
                                'previous_school_name' => $request->student_status == "Transferee" ? $request->previous_school_name : null,
                                'previous_school_year' => $request->student_status == "Transferee" ? $request->previous_school_year : null,
                                'applied_to_fsuu' => $request->student_status == "Transferee" ? $request->applied_to_fsuu : null,
                                'year_applied' => $request->student_status == "Transferee" ? $request->year_applied : null,
                                'accepted_to_fsuu' => $request->student_status == "Transferee" ? $request->accepted_to_fsuu : null,
                                'year_accepted' => $request->student_status == "Transferee" ? $request->year_accepted : null,
                                'attended_to_fsuu' => $request->student_status == "Transferee" ? $request->attended_to_fsuu : null,
                                'year_attended' => $request->student_status == "Transferee" ? $request->year_attended : null,

                                'scholarship_id' =>  $scholarship_id,
                                "apply_scholarship" => $request->apply_scholarship,

                                // Pursuing a Second Degree
                                'intend_to_pursue' => $request->student_status == "Pursuing a Second Degree" ? $request->intend_to_pursue : null,
                                'working_student' => $request->student_status == "Pursuing a Second Degree" ? $request->working_student : null,
                                'employer_name' => $request->student_status == "Pursuing a Second Degree" ? $request->employer_name : null,
                                'employer_address' => $request->student_status == "Pursuing a Second Degree" ? $request->employer_address : null,

                                'updated_by' => auth()->user()->id
                            ])->save();
                        } else {
                            \App\Models\StudentAcademic::create([
                                "profile_id" => $profile_id,
                                'category' => 'Academic Profile',
                                'student_status' => $request->student_status ?? null,
                                'student_level_id' => $request->student_level_id ?? null,
                                'student_strand' => $request->student_strand ?? null,
                                'current_course_id' => $request->current_course_id ?? null,

                                // Top three courses
                                'first_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->first_course_id) ? $request->first_course_id : null),
                                'second_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->second_course_id) ? $request->second_course_id : null),
                                'third_course_id' => $request->student_status == "Pursuing a Second Degree" ? -2 : (isset($request->third_course_id) ? $request->third_course_id : null),

                                // Transferee
                                'previous_school_name' => $request->previous_school_name ?? null,
                                'previous_school_year' => $request->previous_school_year ?? null,
                                'applied_to_fsuu' => $request->applied_to_fsuu ?? null,
                                'year_applied' => $request->year_applied ?? null,
                                'accepted_to_fsuu' => $request->accepted_to_fsuu ?? null,
                                'year_accepted' => $request->year_accepted ?? null,
                                'attended_to_fsuu' => $request->attended_to_fsuu ?? null,
                                'year_attended' => $request->year_attended ?? null,

                                // Scholarship
                                'scholarship_id' => $scholarship_id,
                                "apply_scholarship" => $request->apply_scholarship,

                                // Pursuing a Second Degree
                                'intend_to_pursue' => $request->student_status ?? null,
                                'working_student' => $request->student_status ?? null,
                                'employer_name' => $request->student_status ?? null,
                                'employer_address' => $request->student_status ?? null,

                                'created_by' => auth()->user()->id
                            ]);
                        }


                        // Student Exam Result Update and Create
                        $findStudentExam = \App\Models\StudentExam::where("student_academic_id", $findStudentAcademic->id)
                            ->first();

                        if ($findStudentExam) {
                            $findStudentExam->fill([
                                "student_academic_id" => $findStudentAcademic->id,
                                'exam_schedule_id' => $request->exam_schedule_id,
                                "exam_category_id" => $request->exam_category_id,
                                "schedule_status" => $findStudentExam->or_number === null || " " ? 'Applied' : 'Enrolled',
                                "updated_by" => auth()->user()->id,
                            ])->save();
                        } else {
                            \App\Models\StudentExam::create([
                                "student_academic_id" => $findStudentAcademic->id,
                                'exam_schedule_id' => $request->exam_schedule_id,
                                "exam_category_id" => $request->exam_category_id,
                                "schedule_status" => 'Applied',
                                "category" => 'Walk-In',
                                "status" => 'Active',
                                "created_by" => auth()->user()->id,
                            ]);
                        }
                    }

                    $this->user_persmissions($findUserById->id, $request->user_role_id);

                    $ret = [
                        "success" => true,
                        "message" => "Data updated successfully",
                    ];
                }
            }

            return response()->json($ret, 200);
        } catch (\Throwable $e) {
            $ret = [
                "success" => false,
                "message" => "Data error: " . $e->getMessage(),
            ];
        }

        return response()->json($ret, 200);
    }

    public function update_student_address(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not updated",
        ];

        try {
            $request->validate([
                'auth_password' => 'required',
            ]);

            $error = false;

            // Auth Password for Changes
            $check_password = $this->check_password($request->auth_password);

            if ($check_password) {
                $error = false;
                $ret = [
                    "success" => false,
                    "message" => "Password did not match",
                ];
            }

            if (!$error) {
                $profile_id = $request->profile_id;

                // Update User based on profile_id
                $findProfileById = Profile::find($profile_id);

                if ($findProfileById) {
                    $profile_id = $findProfileById->id;
                    $address_list = $request->address_list;

                    // Student Address Update & Create
                    if (!empty($address_list)) {
                        foreach ($address_list as $key => $value) {
                            if (!empty($value['id'])) {
                                $findStudentAddress = \App\Models\ProfileAddress::where('id', $value['id'])
                                    ->where('category', 'STUDENT ADDRESS')
                                    ->first();

                                if ($findStudentAddress) {
                                    $findStudentAddress->fill([
                                        "profile_id" => $profile_id,
                                        'category' => "STUDENT ADDRESS",
                                        'address' => $value['address'] ?? null,
                                        'city_id' => $value['municipality_id'] ?? null,
                                        'barangay_id' => $value['barangay_id'] ?? null,
                                        'is_home_address' => !empty($value['is_home_address']) && $value['is_home_address'] ? 1 : 0,
                                        'is_current_address' => !empty($value['is_current_address']) && $value['is_current_address'] ? 1 : 0,
                                        'updated_by' => auth()->user()->id
                                    ])->save();
                                }
                            } else {
                                \App\Models\ProfileAddress::create([
                                    "profile_id" => $profile_id,
                                    'category' => "STUDENT ADDRESS",
                                    'address' => $value['address'] ?? null,
                                    'city_id' => $value['municipality_id'] ?? null,
                                    'barangay_id' => $value['barangay_id'] ?? null,
                                    'is_home_address' => !empty($value['is_home_address']) && $value['is_home_address'] ? 1 : 0,
                                    'is_current_address' => !empty($value['is_current_address']) && $value['is_current_address'] ? 1 : 0,
                                    'created_by' => auth()->user()->id
                                ]);
                            }
                        }

                        $ret = [
                            "success" => true,
                            "message" => "Data updated successfully",
                        ];
                    } else {
                        $ret = [
                            "success" => false,
                            "message" => "Address is empty",
                        ];
                    }
                } else {
                    $ret = [
                        "success" => false,
                        "message" => "User not found",
                    ];
                }
            }
        } catch (\Throwable $e) {
            $ret = [
                "success" => false,
                "message" => "Data error: " . $e->getMessage(),
            ];
        }

        $ret += [
            "request" => $request->all()
        ];

        return response()->json($ret);
    }

    public function update_school_attended(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not updated",
        ];

        try {
            $request->validate([
                'auth_password' => 'required',
            ]);

            $error = false;

            // Auth Password for Changes
            $check_password = $this->check_password($request->auth_password);

            if ($check_password) {
                $error = false;
                $ret = [
                    "success" => false,
                    "message" => "Password did not match",
                ];
            }

            if (!$error) {
                $profile_id = $request->profile_id;

                // Find the profile based on profile_id
                $findUserById = Profile::find($profile_id);

                if (!$findUserById) {
                    $ret = [
                        "success" => false,
                        "message" => "User profile not found",
                    ];
                    return response()->json($ret, 404);
                }

                $school_attended_list = $request->school_attended_list;

                if ($findUserById) {
                    if ($profile_id != "") {

                        $existingSchool = \App\Models\ProfileSchoolAttended::where('profile_id', $profile_id)->pluck('id')->toArray();

                        foreach ($existingSchool as $current) {
                            if (!in_array($current, array_column($school_attended_list, 'id'))) {
                                // Deactive existing school if not in new schools
                                \App\Models\ProfileSchoolAttended::where('id', $current)
                                    ->update(['status' => 0]);
                            }
                        }

                        // School Attended Update & Create
                        if (!empty($school_attended_list)) {
                            foreach ($school_attended_list as $key => $value) {
                                if (!empty($value['id'])) {
                                    $findSchoolAttended = \App\Models\ProfileSchoolAttended::where('id', $value['id'])
                                        ->where('school_name', $value['school_name'])
                                        ->first();

                                    if ($findSchoolAttended) {
                                        $findSchoolAttended->fill([
                                            "profile_id" => $profile_id,
                                            'school_level_id' => $value['school_level_id'],
                                            'school_name' => $value['school_name'],
                                            'school_type' => $value['school_type'],
                                            'year_graduated' => $value['year_graduated'],
                                            'school_address' => $value['school_address'],
                                            'status' => 1,
                                            'updated_by' => auth()->user()->id
                                        ])->save();
                                    }
                                } else {
                                    \App\Models\ProfileSchoolAttended::create([
                                        "profile_id" => $profile_id,
                                        'school_level_id' => $value['school_level_id'],
                                        'school_name' => $value['school_name'],
                                        'school_type' => $value['school_type'],
                                        'status' => 1,
                                        'year_graduated' => $value['year_graduated'],
                                        'school_address' => $value['school_address'],
                                        'created_by' => auth()->user()->id
                                    ]);
                                }
                            }
                        }

                        $ret = [
                            "success" => true,
                            "message" => "Data updated successfully",
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            $ret = [
                "success" => false,
                "message" => "Data error: " . $e->getMessage(),
            ];
        }

        return response()->json($ret, 200);
    }

    public function update_family_profile(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not updated",
        ];

        try {
            $request->validate([
                'auth_password' => 'required',
            ]);

            $error = false;

            // Auth Password for Changes
            $check_password = $this->check_password($request->auth_password);

            if ($check_password) {
                $error = false;
                $ret = [
                    "success" => false,
                    "message" => "Password did not match",
                ];
            }

            if (!$error) {
                $profile_id = $request->profile_id;

                $findUserById = Profile::find($profile_id);

                if (!$findUserById) {
                    $ret = [
                        "success" => false,
                        "message" => "User not found",
                    ];
                }

                if ($findUserById) {
                    $dataProfile = [
                        "number_of_brothers" => $request->number_of_brothers ?? null,
                        "number_of_sisters" => $request->number_of_sisters ?? null,
                        "birth_rank" => $request->birth_rank ?? null,
                    ];

                    $findUserById->fill($dataProfile);
                    $findUserById->save();

                    $profile_id = $request->profile_id;
                    $parent_list = $request->parent_list;

                    if ($profile_id != "") {

                        // Family Profile Update & Create
                        $findFamilyAddress = \App\Models\ProfileAddress::where('category', 'FAMILY ADDRESS')
                            ->first();

                        if ($findFamilyAddress) {
                            $findFamilyAddress->fill([
                                "profile_id" => $profile_id,
                                'category' => "FAMILY ADDRESS",
                                'status' => 1,
                                'address' => $request->address ?? null,
                                'city_id' => $request->municipality_id ?? null,
                                'barangay_id' => $request->barangay_id ?? null,
                                'zip_code' => $request->zip_code ?? null,
                                'updated_by' => auth()->user()->id
                            ])->save();
                        } else {
                            \App\Models\ProfileAddress::create([
                                "profile_id" => $profile_id,
                                'category' => "FAMILY ADDRESS",
                                'status' => 1,
                                'address' => $request->address ?? null,
                                'city_id' => $request->municipality_id ?? null,
                                'barangay_id' => $request->barangay_id ?? null,
                                'zip_code' => $request->zip_code ?? null,
                                'created_by' => auth()->user()->id
                            ]);
                        }

                        // Parent Update & Creates
                        $existingParents = \App\Models\ProfileParentInformation::where('profile_id', $profile_id)->pluck('id')->toArray();

                        foreach ($existingParents as $current) {
                            if (!in_array($current, array_column($parent_list, 'id'))) {
                                // Deactive existing parent if not in new parents
                                \App\Models\ProfileParentInformation::where('id', $current)
                                    ->update(['status' => 0]);
                            }
                        }

                        if (!empty($parent_list)) {
                            foreach ($parent_list as $value) {
                                if (!empty($value['id'])) {
                                    $findParent = \App\Models\ProfileParentInformation::where('id', $value['id'])
                                        ->first();

                                    if ($findParent) {
                                        $findParent->fill([
                                            "profile_id" => $profile_id,
                                            "category" => "FAMILY PROFILE",
                                            'firstname' => $value['firstname'] ?? null,
                                            'middlename' => $value['middlename'] ?? null,
                                            'lastname' => $value['lastname'] ?? null,
                                            'name_ext' => $value['name_ext'] ?? null,
                                            'birthdate' => $value['birthdate'] ?? null,
                                            'age' => $value['age'] ?? null,
                                            'occupation' => $value['occupation'] ?? null,
                                            'contact_number' => $value['contact_number'] ?? null,
                                            'relation' => $value['relation'] ?? null,
                                            'status' => 1,
                                            'updated_by' => auth()->user()->id
                                        ])->save();
                                    }
                                } else {
                                    \App\Models\ProfileParentInformation::create([
                                        "profile_id" => $profile_id,
                                        "category" => "FAMILY PROFILE",
                                        'firstname' => $value['firstname'] ?? null,
                                        'middlename' => $value['middlename'] ?? null,
                                        'lastname' => $value['lastname'] ?? null,
                                        'name_ext' => $value['name_ext'] ?? null,
                                        'birthdate' => $value['birthdate'] ?? null,
                                        'age' => $value['age'] ?? null,
                                        'occupation' => $value['occupation'] ?? null,
                                        'contact_number' => $value['contact_number'] ?? null,
                                        'relation' => $value['relation'] ?? null,
                                        'status' => 1,
                                        'created_by' => auth()->user()->id
                                    ]);
                                }
                            }
                        }
                    }

                    $this->user_persmissions($findUserById->id, $request->user_role_id);

                    $ret = [
                        "success" => true,
                        "message" => "Data updated successfully",
                    ];
                }
            }
        } catch (\Throwable $e) {
            $ret = [
                "success" => false,
                "message" => "Data error: " . $e->getMessage(),
            ];
        }

        return response()->json($ret);
    }

    public function update_emergency_contact(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not updated",
        ];

        try {
            $request->validate([
                'auth_password' => 'required',
            ]);

            $error = false;

            // Auth Password for Changes
            $check_password = $this->check_password($request->auth_password);

            if ($check_password) {
                $error = false;
                $ret = [
                    "success" => false,
                    "message" => "Password did not match",
                ];
            }

            if (!$error) {
                $profile_id = $request->profile_id;

                $findUserById = Profile::find($profile_id);

                if (!$findUserById) {
                    $ret = [
                        "success" => false,
                        "message" => "User not found",
                    ];
                }

                if ($findUserById) {
                    $profile_id = $request->profile_id;

                    if ($profile_id != "") {

                        // Emergency Contact Guardian Update & Create
                        $findEmergencyContact = \App\Models\ProfileParentInformation::where('profile_id', $profile_id)
                            ->where('category', 'GUARDIAN')
                            ->first();

                        if ($findEmergencyContact) {
                            $findEmergencyContact->fill([
                                "category" => "GUARDIAN",
                                'lastname' => $request->guardian_lastname ?? null,
                                'firstname' => $request->guardian_firstname ?? null,
                                'middlename' => $request->guardian_middlename ?? null,
                                'relation' => $request->relation ?? null,
                                'contact_number' => $request->contact_number ?? null,
                                'address' => $request->address ?? null,
                                'updated_by' => auth()->user()->id
                            ])->save();
                        } else {
                            \App\Models\ProfileParentInformation::create([
                                "profile_id" => $profile_id,
                                "category" => "GUARDIAN",
                                'lastname' => $request->guardian_lastname ?? null,
                                'firstname' => $request->guardian_firstname ?? null,
                                'middlename' => $request->guardian_middlename ?? null,
                                'relation' => $request->relation ?? null,
                                'contact_number' => $request->contact_number ?? null,
                                'address' => $request->address ?? null,
                                'created_by' => auth()->user()->id
                            ]);
                        }

                        // Emergency Contact Spouses Update & Create
                        $findSpouse = \App\Models\ProfileSpouse::where('profile_id', $profile_id)
                            ->first();

                        if ($findSpouse) {
                            $findSpouse->fill([
                                "profile_id" => $profile_id,
                                'fullname' => $request->fullname ?? null,
                                'contact_number' => $request->spouse_contact_number ?? null,
                                'address' => $request->spouse_address ?? null,
                                'civil_status_id' => 2,
                                'occupation' => $request->occupation ?? null,
                                'updated_by' => auth()->user()->id
                            ])->save();
                        } else {
                            \App\Models\ProfileSpouse::create([
                                "profile_id" => $profile_id,
                                'fullname' => $request->fullname ?? null,
                                'contact_number' => $request->spouse_contact_number ?? null,
                                'address' => $request->spouse_address ?? null,
                                'civil_status_id' => 2,
                                'occupation' => $request->occupation ?? null,
                                'created_by' => auth()->user()->id
                            ]);
                        }

                        // Student Insurance Update & Create
                        $findInsurance = \App\Models\ProfileInsurance::where('profile_id', $profile_id)
                            ->first();

                        if ($findInsurance) {
                            $findInsurance->fill([
                                "profile_id" => $profile_id,
                                'sponsor' => $request->sponsor ?? null,
                                'monthly_income' => $request->monthly_income ?? null,
                                'fullname' => $request->beneficiary_fullname ?? null,
                                'birthdate' => $request->sponsor_birthdate ?? null,
                                'updated_by' => auth()->user()->id
                            ])->save();
                        } else {
                            \App\Models\ProfileInsurance::create([
                                "profile_id" => $profile_id,
                                'sponsor' => $request->sponsor ?? null,
                                'monthly_income' => $request->monthly_income ?? null,
                                'monthly_income' => $request->monthly_income ?? null,
                                'fullname' => $request->beneficiary_fullname ?? null,
                                'fullname' => $request->fullname ?? null,
                                'birthdate' => $request->sponsor_birthdate ?? null,
                                'created_by' => auth()->user()->id
                            ]);
                        }

                        $ret = [
                            "success" => true,
                            "message" => "Data updated successfully",
                        ];
                    }
                }
            }

            return response()->json($ret, 200);
        } catch (\Throwable $e) {
            $ret = [
                "success" => false,
                "message" => "Data error: " . $e->getMessage(),
            ];
        }

        return response()->json($ret);
    }

    public function update_academic_profile(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not updated",
        ];


        try {
            $request->validate([
                'auth_password' => 'required',
            ]);

            $error = false;

            // Auth Password for Changes
            $check_password = $this->check_password($request->auth_password);

            if ($check_password) {
                $error = false;
                $ret = [
                    "success" => false,
                    "message" => "Password did not match",
                ];
            }

            if (!$error) {
                $profile_id = $request->profile_id;

                $findUserById = Profile::find($profile_id);

                if (!$findUserById) {
                    $ret = [
                        "success" => false,
                        "message" => "User not found",
                    ];
                }

                if ($findUserById) {
                    $profile_id = $request->profile_id;

                    if ($profile_id != "") {

                        // Academic Profile Update & Create
                        $findStudentAcademic = \App\Models\StudentAcademic::where('profile_id', $profile_id)
                            ->where('category', 'Academic Profile')
                            ->first();

                        if ($findStudentAcademic) {
                            $findStudentAcademic->fill([
                                "profile_id" => $profile_id,
                                'student_status' => $request->student_status ?? null,
                                'student_level_id' => $request->student_level_id ?? null,
                                'student_strand' => $request->student_level_id == 4 ? $request->student_strand : null,
                                'current_course_id' => $request->student_level_id == 5 ? $request->current_course_id : null,

                                // Top three courses
                                'first_course_id' => $request->first_course_id,
                                'second_course_id' => $request->second_course_id,
                                'third_course_id' => $request->third_course_id ?? null,

                                // Transferee
                                'previous_school_name' => $request->student_status == "Transferee" ? $request->previous_school_name : null,
                                'previous_school_year' => $request->student_status == "Transferee" ? $request->previous_school_year : null,
                                'applied_to_fsuu' => $request->student_status == "Transferee" ? $request->applied_to_fsuu : null,
                                'year_applied' => $request->student_status == "Transferee" && $request->applied_to_fsuu == "No" ? null :
                                    $request->year_applied,
                                'accepted_to_fsuu' => $request->student_status == "Transferee" ? $request->accepted_to_fsuu : null,
                                'year_accepted' => $request->student_status == "Transferee" && $request->accepted_to_fsuu == "No" ? null :
                                    $request->year_accepted,
                                'attended_to_fsuu' => $request->student_status == "Transferee" ? $request->attended_to_fsuu : null,
                                'year_attended' => $request->student_status == "Transferee" && $request->attended_to_fsuu == "No" ? null :
                                    $request->year_attended,

                                // Pursuing a Second Degree
                                'intend_to_pursue' => $request->student_status == "Pursuing a Second Degree" ? $request->intend_to_pursue : null,
                                'working_student' => $request->student_status == "Pursuing a Second Degree" ? $request->working_student : null,
                                'employer_name' => $request->student_status == "Pursuing a Second Degree" ? $request->employer_name : null,
                                'employer_address' => $request->student_status == "Pursuing a Second Degree" ? $request->employer_address : null,

                                'updated_by' => auth()->user()->id
                            ])->save();
                        } else {
                            \App\Models\StudentAcademic::create([
                                "profile_id" => $profile_id,
                                'category' => 'Academic Profile',
                                'student_status' => $request->student_status ?? null,
                                'student_level_id' => $request->student_level_id ?? null,
                                'student_strand' => $request->student_strand ?? null,
                                'current_course_id' => $request->current_course_id ?? null,

                                // Top three courses
                                'first_course_id' => $request->first_course_id ?? null,
                                'second_course_id' => $request->second_course_id ?? null,
                                'third_course_id' => $request->third_course_id ?? null,

                                // Transferee
                                'previous_school_name' => $request->previous_school_name ?? null,
                                'previous_school_year' => $request->previous_school_year ?? null,
                                'applied_to_fsuu' => $request->applied_to_fsuu ?? null,
                                'year_applied' => $request->year_applied ?? null,
                                'accepted_to_fsuu' => $request->accepted_to_fsuu ?? null,
                                'year_accepted' => $request->year_accepted ?? null,
                                'attended_to_fsuu' => $request->attended_to_fsuu ?? null,
                                'year_attended' => $request->year_attended ?? null,

                                // Pursuing a Second Degree
                                'intend_to_pursue' => $request->student_status == "Pursuing a Second Degree" ? $request->intend_to_pursue : null,
                                'working_student' => $request->student_status == "Pursuing a Second Degree" ? $request->working_student : null,
                                'employer_name' => $request->student_status == "Pursuing a Second Degree" ? $request->employer_name : null,
                                'employer_address' => $request->student_status == "Pursuing a Second Degree" ? $request->employer_address : null,

                                'created_by' => auth()->user()->id
                            ]);
                        }


                        $ret = [
                            "success" => true,
                            "message" => "Data updated successfully",
                        ];
                    }
                }
            }

            return response()->json($ret, 200);
        } catch (\Throwable $e) {
            $ret = [
                "success" => false,
                "message" => "Data error: " . $e->getMessage(),
            ];
        }

        return response()->json($ret);
    }

    public function update_additional_information(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not updated",
        ];

        try {
            $request->validate([
                'auth_password' => 'required',
            ]);

            $error = false;

            // Auth Password for Changes
            $check_password = $this->check_password($request->auth_password);

            if ($check_password) {
                $error = false;
                $ret = [
                    "success" => false,
                    "message" => "Password did not match",
                ];
            }

            if (!$error) {
                $profile_id = $request->profile_id;

                $findProfile = Profile::find($profile_id);

                if (!$findProfile) {
                    $ret = [
                        "success" => false,
                        "message" => "User not found",
                    ];
                }

                if ($findProfile) {
                    if ($profile_id != "") {
                        // Additional Information Update & Create
                        $findStudentAcademic = \App\Models\StudentAcademic::where('profile_id', $profile_id)
                            ->where('category', 'Additional Information')
                            ->first();

                        $scholarship_id = $request->scholarship_id ? implode(', ', $request->scholarship_id) : '';
                        $decision_factors = $request->decision_factors ? implode(', ', $request->decision_factors) : '';

                        if ($findStudentAcademic) {
                            $findStudentAcademic->fill([
                                'heard_about_fsuu' => $request->heard_about_fsuu ?? null,
                                'decision_influence' => $request->decision_influence ?? null,
                                'decision_factors' =>  $decision_factors,
                                'other_factors' => $request->other_factors ?? null,
                                'scholarship_id' =>  $scholarship_id,
                                'updated_by' => auth()->user()->id
                            ])->save();
                        } else {
                            \App\Models\StudentAcademic::create([
                                "profile_id" => $profile_id,
                                'category' => 'Additional Information',
                                'heard_about_fsuu' => $request->heard_about_fsuu ?? null,
                                'decision_influence' => $request->decision_influence ?? null,
                                'decision_factors' =>  $decision_factors,
                                'other_factors' => $request->other_factors ?? null,
                                'scholarship_id' =>  $scholarship_id,
                                'created_by' => auth()->user()->id
                            ]);
                        }

                        $ret = [
                            "success" => true,
                            "message" => "Data updated successfully",
                        ];
                    }
                }
            }

            return response()->json($ret, 200);
        } catch (\Throwable $e) {
            $ret = [
                "success" => false,
                "message" => "Data error: " . $e->getMessage(),
            ];
        }

        return response()->json($ret);
    }

    public function update_student_exam_schedule(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not updated",
            "request" => $request->all()
        ];

        try {
            $request->validate([
                'auth_password' => 'required',
            ]);

            $error = false;

            // Auth Password for Changes
            $check_password = $this->check_password($request->auth_password);

            if ($check_password) {
                $error = false;
                $ret = [
                    "success" => false,
                    "message" => "Password did not match",
                ];
            }

            if (!$error) {
                $dataAvailableSlot = RefExamSchedule::select([
                    DB::raw("slots"),
                ])
                    ->find($request->exam_schedule_id);

                if ($dataAvailableSlot) {
                    $dataCountStudentExam = StudentExam::where('exam_schedule_id', $request->exam_schedule_id)
                        ->where('schedule_status', 'Approved')
                        ->count();

                    $countStudentExam = 0;
                    if ($dataCountStudentExam > 0) {
                        $countStudentExam = $dataCountStudentExam;
                    }

                    $available_slots = $dataAvailableSlot->slots - $countStudentExam;


                    if ($available_slots > 0) {
                        $findStudentExam = StudentExam::find($request->id);

                        if ($findStudentExam) {
                            $updatefindStudentExam = $findStudentExam->fill([
                                "schedule_status" => $request->schedule_status,
                                "updated_by" => auth()->user()->id,
                            ]);

                            if ($updatefindStudentExam->save()) {
                                $ret = [
                                    "success" => true,
                                    "message" => "Data updated successfully",
                                ];
                            }
                        }
                    } else {
                        $ret = [
                            "success" => false,
                            "message" => "No available slot",
                        ];
                    }
                } else {
                    $ret = [
                        "success" => false,
                        "message" => "No available slot",
                    ];
                }

                // $ret += [
                //     "countStudentExam" => $countStudentExam
                // ];
            }
            return response()->json($ret, 200);
        } catch (\Throwable $e) {
            $ret = [
                "success" => false,
                "message" => "Data error: " . $e->getMessage(),
            ];
        }

        return response()->json($ret);
    }

    // Multiple Delete
    public function multiple_archived_applicant(Request $request)
    {
        $ret = [
            'success' => false,
            'message' => 'Data not archived!',
            'data' => $request->ids
        ];

        if ($request->has('ids') && count($request->ids) > 0) {
            foreach ($request->ids as $key => $value) {
                $examApplicant = StudentExam::find($value);
                if ($examApplicant) {
                    if ($request->status == "Active") {
                        $examApplicant->fill([
                            'deleted_by' => null,
                            'deleted_at' => null,
                            'status' => 'Active'
                        ])->save();
                    } else if ($request->status == "Archived") {
                        $examApplicant->fill([
                            'deleted_by' => auth()->user()->id,
                            'deleted_at' => now(),
                            'status' => 'Archived'
                        ])->save();
                    }
                }
            }

            $ret = [
                'success' => true,
                'message' => 'Data ' . ($request->status == 'Active' ? 'activated' : 'archived') . ' successfully!',
            ];
        }

        return response()->json($ret, 200);
    }

    public function multiple_applicant_auth(Request $request)
    {
        $ret = [
            'success' => false,
            'message' => 'Data not updated!',
            'data' => $request->ids
        ];

        try {
            $request->validate([
                'newValues' => 'required',
                'auth_password' => 'required'
            ]);

            $error = false;

            // Auth Password for Changes
            $check_password = $this->check_password($request->auth_password);

            if ($check_password) {
                $error = false;
                $ret = [
                    "success" => false,
                    "message" => "Password did not match",
                ];
            }

            if (!$error) {
                $exam_category_id = intval($request->exam_category_id) ?? null;
                $exam_sheet_number = $request->exam_sheet_number ?? null;
                $exam_status = $request->exam_status ?? null;
                $newValues = $request->newValues ?? null;
                $ids = array_column($newValues, 'id') ?? null;
                $status = array_column($newValues, 'exam_status') ?? null;
                $link_origin = $request->link_origin ?? null;
                $status = $request->status ?? null;

                if ($request->from == "Approval Schedule List View") {
                    $this->exam_schedule_approval($exam_category_id, $newValues, $link_origin);
                    $ret = [
                        'success' => true,
                        'message' => 'Data updated successfully!',
                    ];
                } else if ($request->from == "Exam Status List View") {
                    $this->exam_status($exam_sheet_number, $ids, $link_origin, $newValues, $exam_status, $status);
                    $ret = [
                        'success' => true,
                        'message' => 'Data updated successfully!',
                    ];
                } else if ($request->from == "Exam Result List View") {
                    $this->exam_result($link_origin, $newValues, $exam_category_id);
                    $ret = [
                        'success' => true,
                        'message' => 'Data updated successfully!',
                    ];
                }
            }

            return response()->json($ret, 200);
        } catch (\Throwable $e) {
            $ret = [
                "success" => false,
                "message" => "Data error: " . $e->getMessage(),
            ];
        }

        $ret += [
            "request" => $request->all(),
        ];

        return response()->json($ret, 200);
    }

    // Multiple Update Approval Status
    private function exam_schedule_approval($exam_category_id, $newValues, $link_origin)
    {
        $exam_category_id = intval($exam_category_id);
        $dataAvailableSlot = RefExamSchedule::select([
            "slots",
        ])->find($exam_category_id);

        if ($dataAvailableSlot && $dataAvailableSlot->slots) {
            if ($dataAvailableSlot->slots >= count($newValues)) {

                foreach ($newValues as $newValue) {
                    $examApplicant = StudentExam::find($newValue['id']);
                    if ($examApplicant) {
                        $examApplicantUpdate = $examApplicant->fill([
                            'or_number' => $newValue['or_number'] ?? null,
                            'schedule_status' => 'Approved',
                            'exam_status' => 'Not Taken',
                            'updated_by' => auth()->user()->id
                        ]);

                        if ($examApplicantUpdate->save()) {
                            $studentAcadId = StudentAcademic::where('id', $examApplicant->student_academic_id)->first();

                            if ($studentAcadId) {
                                // Email : ADMISSION EXAM APPOINTMENT - if schedule_status is Approved
                                $findEmail = \App\Models\ProfileContactInformation::where('profile_id', $studentAcadId->profile_id)->where(
                                    'status',
                                    1
                                )->latest()->first();

                                $findProfileById = Profile::where('id',  $studentAcadId->profile_id)->first();

                                if ($findEmail) {
                                    $send_name = "";
                                    $findUserProfile = Profile::where('user_id', auth()->user()->id)->first();
                                    if ($findUserProfile) {
                                        $send_name = $findUserProfile->firstname . " " . $findUserProfile->lastname;
                                    }

                                    // GENERATE PDF
                                    // Map the Exam Date and Time to Display in PDF
                                    $examDate = "";
                                    $examTime = "";
                                    $findExamDate = \App\Models\RefExamSchedule::where('id', $exam_category_id)->first();
                                    if ($findExamDate) {
                                        $examDate = $findExamDate->exam_date;
                                        $examTime = $findExamDate->time_in . " " . $findExamDate->time_in_meridiem;
                                    }

                                    $findExamCategory = \App\Models\RefExamCategory::where('id', $newValue['exam_category_id'])->first();

                                    $fsuu_bg = base64_encode(file_get_contents(public_path('images/fsuu_logo_wobg.png')));
                                    $fsuu_logo = base64_encode(file_get_contents(public_path('images/logo.png')));
                                    $guidance_logo = base64_encode(file_get_contents(public_path('images/guidance_logo.png')));

                                    // Get the latest profile picture
                                    $profileId = "";
                                    $findProfile = Attachment::where('attachmentable_id', $findProfileById->id)
                                        ->where('file_type', 'image')
                                        ->orderBy('created_at', 'desc')
                                        ->first();

                                    if ($findProfile) {
                                        $profileId = $findProfile->file_path;
                                    }

                                    // Decode the base64 string
                                    $fsuu_bg_data = 'data:image/png;base64,' . $fsuu_bg;
                                    $fsuu_logo_data = 'data:image/png;base64,' . $fsuu_logo;
                                    $guidance_logo_data = 'data:image/png;base64,' . $guidance_logo;

                                    $data = [
                                        'fullname' => $newValue['fullname'],
                                        'exam_category' => $findExamCategory->category,
                                        'exam_date' => $examDate,
                                        'exam_time' => $examTime,
                                        'exam_room' => 'Testing Room',
                                        'or_number' => $newValue['or_number'] ?? null,
                                        'exam_fee' => $findExamCategory->exam_fee,
                                        'fsuu_bg' => $fsuu_bg_data,
                                        'fsuu_logo' => $fsuu_logo_data,
                                        'guidance_logo' => $guidance_logo_data,
                                        'profile_picture' => $profileId,
                                    ];

                                    $pdf = Pdf::loadView('pdf.testing-permit-template', ["applicants" => collect($data)]);

                                    $pdf->getDomPDF()->setHttpContext(
                                        stream_context_create([
                                            'ssl' => [
                                                'allow_self_signed' => TRUE,
                                                'verify_peer' => FALSE,
                                                'verify_peer_name' => FALSE,
                                            ]
                                        ])
                                    );
                                    $pdf->setPaper('A4', 'portrait');

                                    $newPDFPath = 'profiles/profile-' . $findProfileById->id . '/pdfs/testing-permit/' . Str::random(10) . '.pdf';

                                    $pdfPath = Storage::disk('local')->put('public/' . $newPDFPath, $pdf->output());
                                    $pdf->save($pdfPath);

                                    $this->send_email([
                                        'title' => "ADMISSION EXAM APPOINTMENT",
                                        'to_name' => $newValue['fullname'],
                                        'position' => "FSUU GUIDANCE",
                                        'to_email' => $findEmail->email,
                                        'sender_name' => $send_name,
                                        "system_id" => 3,

                                        // Send the email with the PDF attachment
                                        'attachment' => [
                                            [
                                                'url' => public_path('storage/' . $newPDFPath),
                                                'as' => 'Testing_Permit.pdf'
                                            ]
                                        ],

                                    ]);
                                }

                                // Notification
                                if ($findProfileById) {
                                    $this->send_notification([
                                        "title" => "Admission Exam Schedule",
                                        "description" => "Your admission exam schedule was approved",
                                        "link" => "",
                                        "link_origin" => $link_origin,
                                        "userIds" => [$findProfileById->user_id],
                                        "system_id" => 3,
                                    ]);
                                }
                            }
                        }
                    }
                }

                return $data;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    // Multiple Update Exam Status - Taken or Checked
    private function exam_status($ids, $link_origin, $newValues, $exam_status, $status)
    {
        if (is_array($ids) && count($ids) > 0) {
            foreach ($ids as $key => $value) {
                $examApplicant = StudentExam::find('id', $value);
                if ($examApplicant) {
                    if ($exam_status == "Taken") {
                        $examApplicant->fill([
                            'exam_status' => 'Checked'
                        ])->save();
                    }
                }
            }
        } else if (is_array($newValues)) { {
                foreach ($newValues as $newValue) {
                    $examApplicant = StudentExam::find('id', $newValue['id']);

                    if ($examApplicant) {
                        $studentAcadId = StudentAcademic::where('id', $examApplicant->student_academic_id)->first(); // for profile_id

                        $examApplicantUpdate = $examApplicant->fill([
                            'exam_status' => 'Taken',
                            'date_taken' => now(),
                        ]);

                        if ($examApplicantUpdate->save()) {

                            // Email : ADMISSION EXAM (EXAM TAKEN) - if exam_status is Taken
                            $findProfile = \App\Models\Profile::where('id', $studentAcadId->profile_id)->first();
                            $findEmail = \App\Models\ProfileContactInformation::where('profile_id', $studentAcadId->profile_id)->where(
                                'status',
                            )->latest()->first();
                            $findUserById = Profile::where('id', $studentAcadId->profile_id)->first();

                            if ($findEmail) {
                                $send_name = "";
                                $findUserProfile = Profile::where('user_id', auth()->user()->id)->first();
                                if ($findUserProfile) {
                                    $send_name = $findUserProfile->firstname . " " . $findUserProfile->lastname;
                                }

                                $this->send_email([
                                    'title' => "ADMISSION EXAM (EXAM TAKEN)",
                                    'to_name' => $findProfile['firstname'] . " " .
                                        (isset($findProfile['middlename']) ? $findProfile['middlename'] . " " : '') .
                                        $findProfile['lastname'],
                                    'position' => "FSUU GUIDANCE",
                                    'to_email' => $findEmail->email,
                                    'sender_name' => $send_name,
                                    "system_id" => 3,
                                ]);
                            }

                            if ($findUserById) {
                                $this->send_notification([
                                    "title" => "Admission Exam",
                                    "description" => "This is to confirm and verify that you have TAKEN the Admission Exam.",
                                    "link" => "",
                                    "link_origin" => $link_origin,
                                    "userIds" => [$findUserById->user_id],
                                    "system_id" => 3,
                                ]);
                            }
                        } else if ($status == "Checked") {
                            $examApplicantUpdate = $examApplicant->fill([
                                'exam_status' => 'Checked'
                            ]);

                            if ($examApplicantUpdate->save()) {
                                $findUserById = Profile::where('id', $studentAcadId->profile_id)->first();
                                if ($findUserById) {
                                    $this->send_notification([
                                        "title" => "Admission Exam Result",
                                        "description" => "Please check your email for the result of your exam.",
                                        "link" => "",
                                        "link_origin" => $link_origin,
                                        "userIds" => [$findUserById->user_id],
                                        "system_id" => 3,
                                    ]);
                                }
                            }
                        }

                        // Update and Create Exam Sheet Number
                        $findExamSheet = \App\Models\StudentExamResult::where("student_exam_id", $examApplicant['id'])->first();

                        if ($findExamSheet) {
                            $findExamSheet->fill([
                                'exam_sheet_number' => $newValue['exam_sheet_number'],
                                'updated_by' => auth()->user()->id
                            ])->save();
                        } else {
                            \App\Models\StudentExamResult::create([
                                "profile_id" => $studentAcadId['profile_id'],
                                'student_exam_id' => $examApplicant['id'],
                                'exam_sheet_number' => $newValue['exam_sheet_number'],
                                'created_by' => auth()->user()->id
                            ]);
                        }
                    }

                    return 0;
                }
            }
        }
        return 0;
    }

    // Upload Student Exam Result
    public function exam_result($link_origin, $newValues, $exam_category_id)
    {
        if ($newValues->hasFile('file_excel')) {
            $path = $newValues->file('file_excel');
            $importData = ['link_origin' => $link_origin];
            $examCategory = $exam_category_id;
            $import = new ExamResultImport($importData, $examCategory);
            Excel::import($import, $path);

            $ret = $import->getMessage();
        }

        return 0;
    }

    // Send PDF report
    public function student_exam_report_print(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not created",
        ];

        try {
            $request->validate([
                'date_range' => 'required',
                'department_id' => 'required',
                'semester_id' => 'required',
                // 'exam_category_id' => 'required',
            ]);

            // College Exam Result Variables
            // Correctly explode the date_range string to get start and end dates
            $date_start = Carbon::parse($request->date_range[0])->format('Y-m-d');
            $date_end = Carbon::parse($request->date_range[1])->format('Y-m-d');

            // Filter data by date range and semester
            $findExamSchedule = RefExamSchedule::whereBetween('exam_date', [$date_start, $date_end])
                ->where('semester_id', $request->semester_id)
                ->get();

            // Get the semester name
            $semester = "";
            $findSemester = RefSemester::where('id', $request->semester_id)->first();
            if ($findSemester) {
                $semester = $findSemester->semester;
            }

            $sy_from = "";
            $sy_to =  "";

            if (!$findExamSchedule->isEmpty()) {
                // School Year
                $sy_from = $findExamSchedule->first()->sy_from;
                $sy_to = $findExamSchedule->first()->sy_to;

                // Get the student exam data based on the filtered exam schedule
                $findStudentExam = StudentExam::whereIn('exam_schedule_id', $findExamSchedule->pluck('id')->toArray())
                    ->where('exam_result', 'Available')
                    // ->where('exam_category_id', 1) // Filter by exam category| COLLEGE
                    ->get();

                $findStudentAcademic = StudentAcademic::whereIn('id', $findStudentExam->pluck('student_academic_id')->toArray())
                    ->get();

                $findProfile = Profile::whereIn('id', $findStudentAcademic->pluck('profile_id')->toArray());

                $countMale = $findProfile->where("gender", "Male")->count();
                $countFemale = $findProfile->where("gender", "Female")->count();

                if ($findStudentExam->where('exam_category_id', 1)) {

                    if ($request->department_id != 'all') {
                        // Get the department name
                        // Filter data by department
                        $findDepartment = RefDepartment::where('id', $request->department_id)->first();
                        if ($findDepartment) {
                            $department_name = $findDepartment->department_name;

                            $data = $this->student_exam_report_data($findDepartment->id, $department_name, $semester, $sy_from, $sy_to, $countMale, $countFemale);

                            if ($data) {

                                $pdf = Pdf::loadView('pdf.summary-of-student-profiling-report-template', ["applicants" => [$data]]);

                                $pdf->getDomPDF()->setHttpContext(
                                    stream_context_create([
                                        'ssl' => [
                                            'allow_self_signed' => TRUE,
                                            'verify_peer' => FALSE,
                                            'verify_peer_name' => FALSE,
                                        ]
                                    ])
                                );

                                $pdf->setPaper('A4', 'portrait');

                                $newPDFPath = 'Reports/Student Profiling Reports' . Str::random(10) . '.pdf';

                                $pdfPath = Storage::disk('local')->put('public/' . $newPDFPath, $pdf->output());
                                $pdf->save($pdfPath);

                                $this->send_email(
                                    [
                                        'title' => "STUDENT PROFILING REPORT",
                                        'to_email' => 'uno.usadummy@gmail.com',
                                        "system_id" => 3,

                                        // Send the email with the PDF attachment
                                        'attachment' => [
                                            [
                                                'url' => public_path('storage/' . $newPDFPath),
                                                'as' => 'Student Profiling Reports.pdf'
                                            ]
                                        ],
                                    ]
                                );

                                $ret = [
                                    "success" => true,
                                    "message" => "Data sent successfully",
                                ];
                            } else {
                                $ret = [
                                    "success" => false,
                                    "message" => "No data found",
                                ];
                            }
                        }
                    } else {
                        // ALL DEPARTMENT
                        $findRefDepartments = RefDepartment::all();

                        $data = [];

                        foreach ($findRefDepartments as $key => $value) {
                            $department_name = $value->department_name;
                            $student_exam_report_data = $this->student_exam_report_data($value->id, $department_name, $semester, $sy_from, $sy_to, $countMale, $countFemale);

                            if ($student_exam_report_data) {
                                $data[] = $student_exam_report_data;
                            }
                        }

                        if (count($data) > 0) {
                            $pdf = Pdf::loadView('pdf.summary-of-student-profiling-report-template', ["applicants" => $data]);

                            $pdf->getDomPDF()->setHttpContext(
                                stream_context_create([
                                    'ssl' => [
                                        'allow_self_signed' => TRUE,
                                        'verify_peer' => FALSE,
                                        'verify_peer_name' => FALSE,
                                    ]
                                ])
                            );

                            $pdf->setPaper('A4', 'portrait');

                            $newPDFPath = 'Reports/Student Profiling Reports' . Str::random(10) . '.pdf';

                            $pdfPath = Storage::disk('local')->put('public/' . $newPDFPath, $pdf->output());
                            $pdf->save($pdfPath);

                            $this->send_email(
                                [
                                    'title' => "STUDENT PROFILING REPORT",
                                    'to_email' => 'uno.usadummy@gmail.com',
                                    "system_id" => 3,

                                    // Send the email with the PDF attachment
                                    'attachment' => [
                                        [
                                            'url' => public_path('storage/' . $newPDFPath),
                                            'as' => 'Student Profiling Reports.pdf'
                                        ]
                                    ],
                                ]
                            );

                            $ret = [
                                "success" => true,
                                "message" => "Data sent successfully",
                            ];
                        } else {
                            $ret = [
                                "success" => false,
                                "message" => "No data found",
                            ];
                        }
                    }
                } else {
                    $ret = [
                        "success" => false,
                        "message" => "No data found",
                    ];
                }
            } else {
                $ret = [
                    "success" => false,
                    "message" => "No data found",
                ];
            }
        } catch (\Throwable $e) {
            $ret = [
                "success" => false,
                "message" => "Data error: " . $e->getMessage(),
            ];
        }

        // return response()->json([
        //     'data' => $data
        // ]);
        return response()->json($ret);
    }

    private function student_exam_report_data($department_id, $department_name, $semester, $sy_from, $sy_to, $countMale, $countFemale)
    {
        $firstCourse = [];
        $secondCourse = [];
        $thirdCourse = [];
        $strandCourseIds = [];

        $firstCourseCounts = [];
        $secondCourseCounts = [];
        $thirdCourseCounts = [];
        $strandCourseCounts = [];

        $totalFirstCourse = 0;
        $totalSecondCourse = 0;
        $totalThirdCourse = 0;
        $totalStrandCourse = 0;

        $counters = [
            'en' => ['L' => 0, 'A' => 0, 'H' => 0],
            'mt' => ['L' => 0, 'A' => 0, 'H' => 0],
            'sc' => ['L' => 0, 'A' => 0, 'H' => 0],
            'crs' => ['L' => 0, 'A' => 0, 'H' => 0],
        ];

        $vdCount = [
            'vd' =>  ['Below Average' => 0, 'Average' => 0, 'Above Average' => 0],
        ];

        $findCourse = RefCourse::where('department_id', $department_id)->get();
        if (!$findCourse->isEmpty()) {
            $findStudentAcademic = StudentAcademic::whereIn('current_course_id', $findCourse->pluck('id')->toArray())
                ->where('category', 'Academic Profile')
                ->get();

            if (!$findStudentAcademic->isEmpty()) {

                $findStudentExam = StudentExam::whereIn('student_academic_id', $findStudentAcademic->pluck('id')->toArray())
                    ->get();

                // Get exam results
                $findStudentExamResult = StudentExamResult::whereIn('student_exam_id', $findStudentExam->pluck('id')->toArray())
                    ->get();

                if (!$findStudentExamResult->isEmpty()) {
                    $qualityIndexes = ['en', 'mt', 'sc', 'crs'];
                    $qualityScores = ['L', 'A', 'H'];
                    $counters = [];

                    foreach ($qualityIndexes as $index) {
                        foreach ($qualityScores as $score) {
                            $counters[$index][$score] = 0;
                        }
                    }

                    // Count occurrences
                    foreach ($findStudentExamResult as $result) {
                        foreach ($qualityIndexes as $index) {
                            $qualityIndex = $index . '_quality_index';
                            if (in_array($result->$qualityIndex, $qualityScores)) {
                                $counters[$index][$result->$qualityIndex]++;
                            }
                        }
                    }

                    $vdIndexes = ['vd'];
                    $vdScore = ['Below Average', 'Average', 'Above Average'];
                    $vdCount = [];

                    foreach ($vdIndexes as $index) {
                        foreach ($vdScore as $score) {
                            $vdCount[$index][$score] = 0;
                        }
                    }

                    // Count occurrences
                    foreach ($findStudentExamResult as $result) {
                        foreach ($vdIndexes as $index) {
                            $vdIndex = $index;
                            if (in_array($result->$vdIndex, $vdScore)) {
                                $vdCount[$index][$result->$vdIndex]++;
                            }
                        }
                    }
                }

                // Define a mapping for special course IDs to names
                $specialCourseNames = [
                    -1 => 'Undecided',
                    -2 => 'NO CHOSEN COURSE',
                    -3 => 'Not in the list',
                ];

                // Find First Course
                $firstCourseIds = $findStudentAcademic->pluck('first_course_id')->toArray();
                $firstCourseIds = array_filter($firstCourseIds, function ($value) {  // Ensure all values are strings or integers
                    return is_string($value) || is_integer($value);
                });

                $firstCourseIds = array_map('strval', $firstCourseIds);  // Optionally, convert all values to strings for consistency
                $firstCourseIdCounts = array_count_values($firstCourseIds);  // Count occurrences of each first_course_id
                $uniqueFirstCourseIds = array_keys($firstCourseIdCounts);
                $totalFirstCourse = count($uniqueFirstCourseIds);

                foreach ($specialCourseNames as $specialId => $name) {   // Check and handle special course IDs
                    if (in_array($specialId, $uniqueFirstCourseIds)) {
                        $count = $firstCourseIdCounts[$specialId] ?? 0;
                        $firstCourseCounts[$name] = $count;
                        while ($count-- > 0) {
                            $firstCourse[] = $name; // Include the special name for each occurrence
                        }
                    }
                }

                // Filter out special IDs before fetching course names
                $filteredUniqueFirstCourseIds = array_filter($uniqueFirstCourseIds, function ($id) use ($specialCourseNames) {
                    return !array_key_exists($id, $specialCourseNames);
                });
                $findCourseName = RefCourse::whereIn('id', $filteredUniqueFirstCourseIds)->get();

                foreach ($findCourseName as $course) {  // Map course names back to their counts
                    $courseName = $course->course_name;
                    $courseId = $course->id;
                    $count = $firstCourseIdCounts[$courseId] ?? 0;
                    while ($count-- > 0) {
                        $firstCourse[] = $courseName; // This will include duplicates
                    }
                    $firstCourseCounts[$courseName] = $count;
                }

                // Find Second Course
                $secondCourseIds = $findStudentAcademic->pluck('second_course_id')->toArray();
                $secondCourseIds = array_filter($secondCourseIds, function ($value) {
                    return is_string($value) || is_integer($value);
                });

                $secondCourseIds = array_map('strval', $secondCourseIds);
                $secondCourseIdCounts = array_count_values($secondCourseIds);
                $uniqueSecondCourseIds = array_keys($secondCourseIdCounts);
                $totalSecondCourse = count($uniqueSecondCourseIds);

                foreach ($specialCourseNames as $specialId => $name) {   // Check and handle special course IDs
                    if (in_array($specialId, $uniqueSecondCourseIds)) {
                        $count = $secondCourseIdCounts[$specialId] ?? 0;
                        $secondCourseCounts[$name] = $count;
                        while ($count-- > 0) {
                            $secondCourse[] = $name; // Include the special name for each occurrence
                        }
                    }
                }

                // Find Third Course
                $thirdCourseIds = $findStudentAcademic->pluck('third_course_id')->toArray();
                $thirdCourseIds = array_filter($thirdCourseIds, function ($value) {
                    return is_string($value) || is_integer($value);
                });

                $thirdCourseIds = array_map('strval', $thirdCourseIds);
                $thirdCourseIdCounts = array_count_values($thirdCourseIds);
                $uniqueThirdCourseIds = array_keys($thirdCourseIdCounts);
                $totalThirdCourse = count($uniqueThirdCourseIds);

                foreach ($specialCourseNames as $specialId => $name) {   // Check and handle special course IDs
                    if (in_array($specialId, $uniqueThirdCourseIds)) {
                        $count = $thirdCourseIdCounts[$specialId] ?? 0;
                        $thirdCourseCounts[$name] = $count;
                        while ($count-- > 0) {
                            $thirdCourse[] = $name; // Include the special name for each occurrence
                        }
                    }
                }

                // Find Strand Course
                $strandCourseIds = $findStudentAcademic->pluck('student_strand')->toArray();
                $strandCourseIds = array_filter($strandCourseIds, function ($value) {
                    return is_string($value) || is_integer($value);
                });

                $strandCourseIds = array_map('strval', $strandCourseIds);
                $strandCourseCounts = array_count_values($strandCourseIds);
                $uniqueStrandCourseIds = array_keys($strandCourseIds);
                $totalStrandCourse = count($uniqueStrandCourseIds);

                foreach ($specialCourseNames as $specialId => $name) {   // Check and handle special course IDs
                    if (in_array($specialId, $uniqueStrandCourseIds)) {
                        $count = $strandCourseCounts[$specialId] ?? 0;
                        $strandCourseCounts[$name] = $count;
                        while ($count-- > 0) {
                            $strandCourse[] = $name; // Include the special name for each occurrence
                        }
                    }
                }

                // GENERATE PDF
                $fsuu_bg = base64_encode(file_get_contents(public_path('images/fsuu_logo_wobg.png')));
                $fsuu_logo = base64_encode(file_get_contents(public_path('images/logo.png')));
                $guidance_logo = base64_encode(file_get_contents(public_path('images/guidance_logo.png')));

                // Decode the base64 string
                $fsuu_bg_data = 'data:image/png;base64,' . $fsuu_bg;
                $fsuu_logo_data = 'data:image/png;base64,' . $fsuu_logo;
                $guidance_logo_data = 'data:image/png;base64,' . $guidance_logo;

                $data = [
                    'sy_from' => $sy_from,
                    'sy_to' => $sy_to,
                    'department_name' => $department_name,
                    'semester' => $semester,
                    'total' => $findStudentAcademic->count(),

                    'countMale' => $countMale,
                    'countFemale' => $countFemale,

                    'en_qi_L' => $counters['en']['L'],
                    'en_qi_A' => $counters['en']['A'],
                    'en_qi_H' => $counters['en']['H'],
                    'mt_qi_L' => $counters['mt']['L'],
                    'mt_qi_A' => $counters['mt']['A'],
                    'mt_qi_H' => $counters['mt']['H'],
                    'sc_qi_L' => $counters['sc']['L'],
                    'sc_qi_A' => $counters['sc']['A'],
                    'sc_qi_H' => $counters['sc']['H'],
                    'crs_qi_L' => $counters['crs']['L'],
                    'crs_qi_A' => $counters['crs']['A'],
                    'crs_qi_H' => $counters['crs']['H'],

                    'vd_BA' => $vdCount['vd']['Below Average'],
                    'vd_A' => $vdCount['vd']['Average'],
                    'vd_AA' => $vdCount['vd']['Above Average'],

                    'first_course' => $firstCourse,
                    'second_course' => $secondCourse,
                    'third_course' => $thirdCourse,

                    'firstCourseCounts' => $firstCourseCounts,
                    'secondCourseCounts' => $secondCourseCounts,
                    'thirdCourseCounts' => $thirdCourseCounts,
                    "strandCourseCounts" => $strandCourseCounts,

                    'totalFirstCourse' => $totalFirstCourse,
                    'totalSecondCourse' => $totalSecondCourse,
                    'totalThirdCourse' => $totalThirdCourse,
                    'totalStrandCourse' => $totalStrandCourse,

                    'fsuu_bg' => $fsuu_bg_data,
                    'fsuu_logo' => $fsuu_logo_data,
                    'guidance_logo' => $guidance_logo_data,
                ];

                return $data;
            }

            return 0;
        } else {
            return 0;
        }
    }
}
