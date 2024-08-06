<?php

namespace App\Imports;

use App\Models\FacultyLoad;
use App\Models\Profile;
use App\Models\RefBuilding;
use App\Models\RefDaySchedule;
use App\Models\RefDepartment;
use App\Models\RefFloor;
use App\Models\RefRoom;
use App\Models\RefSchoolYear;
use App\Models\RefSection;
use App\Models\RefSemester;
use App\Models\RefSubject;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class FacultyLoadImport implements ToCollection
{
    private $ret = [];

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $ret = [
            "success" => false,
            "message" => "Excel Data Not Uploaded",
        ];

        $header = ["", "", "", "", "", "", "", "", "", "", ""];
        $semester = "";
        $school_year = "";
        $data = [];

        foreach ($collection as $key => $value) {
            if ($key == 4) {
                $column4 = $value[0];
                if ($column4) {
                    $column4Split = explode(",", $column4);
                    $semester = trim($column4Split[0]);
                    $schoolYearTemp = str_replace("School Year", "", $column4Split[1]);
                    $school_year = str_replace(' ', '', trim($schoolYearTemp));
                }
            }

            if ($key == 6) {
                $col0 = strtoupper(str_replace(' ', '', $value[0]));
                $col1 = strtoupper(str_replace(' ', '', $value[1]));
                $col2 = strtoupper(str_replace(' ', '', $value[2]));
                $col3 = strtoupper(str_replace(' ', '', $value[3]));
                $col4 = strtoupper(str_replace(' ', '', $value[4]));
                $col5 = strtoupper(str_replace(' ', '', $value[5]));
                $col6 = strtoupper(str_replace(' ', '', $value[6]));
                $col7 = strtoupper(str_replace(' ', '', $value[7]));
                $col8 = strtoupper(str_replace(' ', '', $value[8]));
                $col9 = strtoupper(str_replace(' ', '', $value[9]));
                $col10 = strtoupper(str_replace(' ', '', $value[10]));

                $header = [$col0, $col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10];
            }

            if ($key >= 8) {
                $data[] = $value;
            }
        }

        // array_walk_recursive($data, function (&$item, $key) {
        //     if (!mb_detect_encoding($item, 'utf-8', true)) {
        //         $item = mb_convert_encoding($item, 'UTF-8', 'auto');
        //     }
        // });

        $ifheader = $header[0] == "FACULTYNO." && $header[1] == "FACULTYNAME" && $header[2] == "SUBJECTCODE" && $header[3] == "SECTION" && $header[4] == "ROOMNO." && $header[5] == "TIME" && $header[6] == "AMORPM" && $header[7] == "DAY" && $header[8] == "TYPE" && $header[9] == "PARENTDEPARTMENT" && $header[10] == "PARENTCOLLEGE";

        if ($ifheader && count($data) > 0) {
            $semester_id = "";
            $school_year_id = "";

            // for semester
            if ($semester) {
                $findSemester = RefSemester::where("semester", $semester)->first();
                if ($findSemester) {
                    $semester_id = $findSemester->id;
                } else {
                    $createSemester = RefSemester::create([
                        "semester" => $semester,
                        'created_by' => auth()->user()->id,
                    ]);
                    $semester_id = $createSemester->id;
                }
            }
            // end for semester

            // for school year
            if ($school_year) {
                $school_year = explode("-", $school_year);
                $school_year_from = $school_year[0];
                $school_year_to = $school_year[1];

                $findSchoolYear = RefSchoolYear::where("sy_from", $school_year_from)
                    ->where("sy_to", $school_year_to)
                    ->first();
                if ($findSchoolYear) {
                    $school_year_id = $findSchoolYear->id;
                } else {
                    $createSchoolYear = RefSchoolYear::create([
                        "sy_from" => $school_year_from,
                        "sy_to" => $school_year_to,
                        'created_by' => auth()->user()->id,
                    ]);
                    $school_year_id = $createSchoolYear->id;
                }
            }
            // end for school year

            // // for data
            foreach ($data as $key => $value) {
                $school_id = trim($value[0]);
                $school_id = mb_convert_encoding($school_id, 'UTF-8', 'auto');
                $faculty_name = trim($value[1]);
                $faculty_name = mb_convert_encoding($faculty_name, 'UTF-8', 'auto');
                $subject_code = trim($value[2]);
                $subject_code = mb_convert_encoding($subject_code, 'UTF-8', 'auto');
                $section = trim($value[3]);
                $section = mb_convert_encoding($section, 'UTF-8', 'auto');
                $room_no = trim($value[4]);
                $room_no = mb_convert_encoding($room_no, 'UTF-8', 'auto');
                $time = trim($value[5]);
                $time = mb_convert_encoding($time, 'UTF-8', 'auto');
                $meridian = trim($value[6]);
                $meridian = mb_convert_encoding($meridian, 'UTF-8', 'auto');
                $day = trim($value[7]);
                $day = mb_convert_encoding($day, 'UTF-8', 'auto');
                $type = trim($value[8]);
                $type = mb_convert_encoding($type, 'UTF-8', 'auto');
                $parent_department = trim($value[9]);
                $parent_department = mb_convert_encoding($parent_department, 'UTF-8', 'auto');
                $parent_college = trim($value[10]);
                $parent_college = mb_convert_encoding($parent_college, 'UTF-8', 'auto');

                $time_in = "";
                $time_out = "";

                // for time
                if ($time) {
                    $time_explode = explode('-', $time);
                    $time_in = $time_explode[0];
                    $time_out = $time_explode[1];
                }
                // end for time

                // for type
                if (!empty($type)) {
                    $type = strtolower($type);
                    $type = $type == "regular" || $type == "full-time" ? "Full-Time" : "Part-Time";
                } else {
                    $type = "Part-Time";
                }
                // end for type

                $user_id = "";
                $profile_id = "";
                $subject_id = "";
                $section_id = "";
                $room_id = "";
                $day_schedule_id = "";
                $department_id = "";

                // for department
                if ($parent_college) {
                    $department = RefDepartment::where("department_name", $parent_college)->first();
                    if ($department) {
                        $department_id = $department->id;
                    } else {
                        $createDepartment = RefDepartment::create([
                            'department_name' => $parent_college,
                            'created_by' => auth()->user()->id
                        ]);
                        $department_id = $createDepartment->id;
                    }
                }
                // end for department

                // for user
                $lastname = "";
                $firstname_arr = [];

                $split_faculty_name = explode(",", $faculty_name);

                foreach ($split_faculty_name as $key => $value) {
                    if ($key == 0) {
                        $lastname = trim($value);
                    } else {
                        $firstname_arr[] = trim($value);
                    }
                }

                $firstname = implode(" ", $firstname_arr);

                $str_firstname = str_replace([" ", "-", ".", ","], "", $firstname);
                $str_lastname = str_replace([" ", "-", ".", ","], "", $lastname);
                $email = "";

                if ($str_firstname) {
                    $email .= $str_firstname[0];
                }
                if ($str_lastname) {
                    $email .= $str_lastname;
                }

                // $email = mb_convert_encoding($email, 'UTF-8', 'auto');

                $remakeEmail = "";
                if ($email) {
                    $remakeEmail = strtolower($email) . "@urios.edu.com";
                }

                $checkEmail = User::where("email", $remakeEmail)->first();

                if ($checkEmail) {
                    $remakeEmail = $email . rand(1, 1000) . "@urios.edu.com";
                }

                // $remakeEmail = mb_convert_encoding($remakeEmail, 'UTF-8', 'auto');

                $checkUsername = User::where("username", $school_id)->first();

                if (!$checkUsername) {
                    $checkProfile = User::where("username", $school_id)
                        ->where("email", $remakeEmail)
                        ->first();

                    if (!$checkProfile) {
                        $createUser = User::create([
                            "username" => $school_id,
                            "email" => $remakeEmail,
                            "email_verified_at" => now(),
                            "password" => Hash::make("Admin123!"),
                            "status" => "Active",
                            "user_role_id" => 5,
                            'created_by' => auth()->user()->id,
                            "remember_token" => Str::random(10),
                        ]);

                        $user_id = $createUser->id;

                        $this->user_persmissions($user_id, 5);
                    } else {
                        $user_id = $checkProfile->id;
                    }
                } else {
                    $user_id = $checkUsername->id;
                }

                // end for user

                // for profile
                $findProfile = Profile::where("user_id", $user_id)->first();
                if ($findProfile) {
                    $profile_id = $findProfile->id;
                } else {
                    $createProfile = Profile::create([
                        "school_id" => $school_id,
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        'created_by' => auth()->user()->id,
                        'user_id' => $user_id,
                        'employment_type' => $type,
                    ]);

                    $profile_id = $createProfile->id;

                    $createProfile->profile_departments()->create([
                        "department_id" => $department_id,
                        'created_by' => auth()->user()->id,
                        'status' => 1
                    ]);
                }
                // end for profile

                // for subject
                if ($subject_code) {
                    $findSubject = RefSubject::where("code", $subject_code)->first();
                    if ($findSubject) {
                        $subject_id = $findSubject->id;
                    } else {
                        $createSubject = RefSubject::create([
                            "code" => $subject_code,
                            'created_by' => auth()->user()->id,
                        ]);
                        $subject_id = $createSubject->id;
                    }
                }
                // end for subject

                // for section
                if ($section) {
                    $findSection = RefSection::where("section", $section)->first();
                    if ($findSection) {
                        $section_id = $findSection->id;
                    } else {
                        $createSection = RefSection::create([
                            "section" => $section,
                            'created_by' => auth()->user()->id,
                        ]);
                        $section_id = $createSection->id;
                    }
                }
                // end for section

                // for room
                if ($room_no) {
                    $building_id = "";
                    $floor_id = "";

                    if (str_replace(" ", "", trim($room_no)) != "TBA") {
                        if (str_replace(" ", "", trim($room_no)) == "FIELD") {
                            $buildingInfo = RefBuilding::where("building", "CBS")->first();

                            if ($buildingInfo) {
                                $building_id = $buildingInfo->id;
                            } else {
                                $createBuilding = RefBuilding::create([
                                    'building' => "CBS",
                                    'created_by' => auth()->user()->id
                                ]);

                                $building_id = $createBuilding->id;
                            }

                            $findFloor = RefFloor::where("floor", "1st Floor")->first();

                            if ($findFloor) {
                                $floor_id = $findFloor->id;
                            } else {
                                $createFloor = RefFloor::create([
                                    'floor' => "1st Floor",
                                    'created_by' => auth()->user()->id
                                ]);

                                $floor_id = $createFloor->id;
                            }
                        } else if (str_replace(" ", "", trim($room_no)) == "HUGGENBERGHALL") {
                            $buildingInfo = RefBuilding::where("building", "CBS")->first();

                            if ($buildingInfo) {
                                $building_id = $buildingInfo->id;
                            } else {
                                $createBuilding = RefBuilding::create([
                                    'building' => "CBS",
                                    'created_by' => auth()->user()->id
                                ]);

                                $building_id = $createBuilding->id;
                            }

                            $findFloor = RefFloor::where("floor", "4th Floor")->first();

                            if ($findFloor) {
                                $floor_id = $findFloor->id;
                            } else {
                                $createFloor = RefFloor::create([
                                    'floor' => "4th Floor",
                                    'created_by' => auth()->user()->id
                                ]);

                                $floor_id = $createFloor->id;
                            }
                        } else if (str_replace(" ", "", trim($room_no)) == "SKILLSLAB") {
                            $buildingInfo = RefBuilding::where("building", "CB")->first();

                            if ($buildingInfo) {
                                $building_id = $buildingInfo->id;
                            } else {
                                $createBuilding = RefBuilding::create([
                                    'building' => "CB",
                                    'created_by' => auth()->user()->id
                                ]);

                                $building_id = $createBuilding->id;
                            }

                            $findFloor = RefFloor::where("floor", "1st Floor")->first();

                            if ($findFloor) {
                                $floor_id = $findFloor->id;
                            } else {
                                $createFloor = RefFloor::create([
                                    'floor' => "1st Floor",
                                    'created_by' => auth()->user()->id
                                ]);

                                $floor_id = $createFloor->id;
                            }
                        } else if (str_replace(" ", "", trim($room_no)) == "SIMULATIONROOM(BAP)") {
                            $buildingInfo = RefBuilding::where("building", "CB")->first();

                            if ($buildingInfo) {
                                $building_id = $buildingInfo->id;
                            } else {
                                $createBuilding = RefBuilding::create([
                                    'building' => "CB",
                                    'created_by' => auth()->user()->id
                                ]);

                                $building_id = $createBuilding->id;
                            }

                            $findFloor = RefFloor::where("floor", "2nd Floor")->first();

                            if ($findFloor) {
                                $floor_id = $findFloor->id;
                            } else {
                                $createFloor = RefFloor::create([
                                    'floor' => "2nd Floor",
                                    'created_by' => auth()->user()->id
                                ]);

                                $floor_id = $createFloor->id;
                            }
                        } else if (str_replace(" ", "", trim($room_no)) == "LIBRARYCLR3" || str_replace(" ", "", trim($room_no)) == "LIBRARYCLR2"  || str_replace(" ", "", trim($room_no)) == "LIBRARYCLR1") {
                            $buildingInfo = RefBuilding::where("building", "CB")->first();

                            if ($buildingInfo) {
                                $building_id = $buildingInfo->id;
                            } else {
                                $createBuilding = RefBuilding::create([
                                    'building' => "CB",
                                    'created_by' => auth()->user()->id
                                ]);

                                $building_id = $createBuilding->id;
                            }

                            $floor_value = "";
                            if (str_replace(" ", "", trim($room_no)) == "LIBRARYCLR3") {
                                $floor_value = "3rd Floor";
                            } else {
                                $floor_value = "2nd Floor";
                            }

                            $findFloor = RefFloor::where("floor", $floor_value)->first();

                            if ($findFloor) {
                                $floor_id = $findFloor->id;
                            } else {
                                $createFloor = RefFloor::create([
                                    'floor' => $floor_value,
                                    'created_by' => auth()->user()->id
                                ]);

                                $floor_id = $createFloor->id;
                            }
                        } else if (str_replace(" ", "", trim($room_no)) == "MORELOSCAMPUS") {
                            $buildingInfo = RefBuilding::where("building", "MORELOS CAMPUS")->first();

                            if ($buildingInfo) {
                                $building_id = $buildingInfo->id;
                            } else {
                                $createBuilding = RefBuilding::create([
                                    'building' => "MORELOS CAMPUS",
                                    'created_by' => auth()->user()->id
                                ]);

                                $building_id = $createBuilding->id;
                            }

                            $findFloor = RefFloor::where("floor", "1st Floor")->first();

                            if ($findFloor) {
                                $floor_id = $findFloor->id;
                            } else {
                                $createFloor = RefFloor::create([
                                    'floor' => "1st Floor",
                                    'created_by' => auth()->user()->id
                                ]);

                                $floor_id = $createFloor->id;
                            }
                        } else if (str_replace(" ", "", trim($room_no)) == "PEHALL1" || str_replace(" ", "", trim($room_no)) == "PEHALL2" || str_replace(" ", "", trim($room_no)) == "PEHALL3" || str_replace(" ", "", trim($room_no)) == "PEHALL4" || str_replace(" ", "", trim($room_no)) == "PEHALL5" || str_replace(" ", "", trim($room_no)) == "PEHALL6") {
                            $buildingInfo = RefBuilding::where("building", "CBS")->first();

                            if ($buildingInfo) {
                                $building_id = $buildingInfo->id;
                            } else {
                                $createBuilding = RefBuilding::create([
                                    'building' => "CBS",
                                    'created_by' => auth()->user()->id
                                ]);

                                $building_id = $createBuilding->id;
                            }

                            $findFloor = RefFloor::where("floor", "1st Floor")->first();

                            if ($findFloor) {
                                $floor_id = $findFloor->id;
                            } else {
                                $createFloor = RefFloor::create([
                                    'floor' => "1st Floor",
                                    'created_by' => auth()->user()->id
                                ]);

                                $floor_id = $createFloor->id;
                            }
                        } else {
                            $room_no_exp = explode(" ", $room_no);
                            $building = count($room_no_exp) > 0 ? ($room_no_exp[0] ? trim($room_no_exp[0]) : "") : "";
                            $floor_exp = count($room_no_exp) > 1 ? ($room_no_exp[1] ? trim($room_no_exp[1]) : "") : "";
                            $floor = "";

                            if ($floor_exp && $floor_exp[0] == "1") {
                                $floor = "1st Floor";
                            } else if ($floor_exp && $floor_exp[0] == "2") {
                                $floor = "2nd Floor";
                            } else if ($floor_exp && $floor_exp[0] == "3") {
                                $floor = "3rd Floor";
                            } else if ($floor_exp && $floor_exp[0] == "4") {
                                $floor = "4th Floor";
                            }

                            if ($building) {
                                $findBuilding = RefBuilding::where("building", $building)->first();

                                if ($findBuilding) {
                                    $building_id = $findBuilding->id;
                                } else {
                                    $createBuilding = RefBuilding::create([
                                        'building' => $building,
                                        'created_by' => auth()->user()->id
                                    ]);

                                    $building_id = $createBuilding->id;
                                }
                            }

                            if ($floor) {
                                $findFloor = RefFloor::where("floor", $floor)->first();

                                if ($findFloor) {
                                    $floor_id = $findFloor->id;
                                } else {
                                    $createFloor = RefFloor::create([
                                        'floor' => $floor,
                                        'created_by' => auth()->user()->id
                                    ]);

                                    $floor_id = $createFloor->id;
                                }
                            }
                        }

                        $findroom = RefRoom::where("room_code", ltrim($room_no))
                            ->where("floor_id", $floor_id)
                            ->where("building_id", $building_id)
                            ->first();
                        if ($findroom) {
                            $room_id = $findroom->id;
                        } else {
                            $createRoom = RefRoom::create([
                                "room_code" => ltrim($room_no),
                                "floor_id" => $floor_id ?? null,
                                "building_id" => $building_id ?? null,
                                'created_by' => auth()->user()->id,
                            ]);
                            $room_id = $createRoom->id;
                        }
                    } else {
                        $findroom = RefRoom::where("room_code", $room_no)
                            ->first();
                        if ($findroom) {
                            $room_id = $findroom->id;
                        } else {
                            $createRoom = RefRoom::create([
                                "room_code" => trim($room_no),
                                'created_by' => auth()->user()->id,
                            ]);
                            $room_id = $createRoom->id;
                        }
                    }
                }
                // end for room

                // for day
                if ($day) {
                    $day_name = "";

                    if ($day == "M") {
                        $day_name = "Monday";
                    } else if ($day == "T") {
                        $day_name = "Tuesday";
                    } else if ($day == "W") {
                        $day_name = "Wednesday";
                    } else if ($day == "TH") {
                        $day_name = "Thursday";
                    } else if ($day == "F") {
                        $day_name = "Friday";
                    } else if ($day == "S") {
                        $day_name = "Saturday";
                    } else if ($day == "SUN") {
                        $day_name = "Sunday";
                    }

                    $findDaySchedule = RefDaySchedule::where("code", $day)->first();
                    if ($findDaySchedule) {
                        $day_schedule_id = $findDaySchedule->id;
                    } else {
                        $createDaySchedule = RefDaySchedule::create([
                            "code" => $day,
                            "name" => $day_name,
                            'created_by' => auth()->user()->id,
                        ]);
                        $day_schedule_id = $createDaySchedule->id;
                    }
                }
                // end for day


                $findFacultyLoad = FacultyLoad::where('profile_id', $profile_id)
                    ->where('subject_id', $subject_id)
                    ->where('section_id', $section_id)
                    ->where('room_id', $room_id)
                    // ->where('time_in', $time_in)
                    // ->where('time_out', $time_out)
                    // ->where('meridian', $meridian)
                    // ->where('day_schedule_id', $day_schedule_id)
                    ->where('school_year_id', $school_year_id)
                    ->where('semester_id', $semester_id)
                    ->where('department_id', $department_id)
                    ->first();

                if (!$findFacultyLoad) {
                    $findFacultyLoad = FacultyLoad::create([
                        'profile_id' => $profile_id,
                        'subject_id' => $subject_id,
                        'section_id' => $section_id,
                        'room_id' => $room_id,
                        // 'time_in' => $time_in,
                        // 'time_out' => $time_out,
                        // 'meridian' => $meridian,
                        // 'day_schedule_id' => $day_schedule_id,
                        'school_year_id' => $school_year_id,
                        'semester_id' => $semester_id,
                        'department_id' => $department_id,
                        'created_by' => auth()->user()->id,
                    ]);
                }

                if ($findFacultyLoad) {
                    $findFacultyLoadSchedule = $findFacultyLoad->faculty_load_schedules()
                        ->where('time_in', $time_in)
                        ->where('time_out', $time_out)
                        ->where('meridian', $meridian)
                        ->where('day_schedule_id', $day_schedule_id)
                        ->first();

                    if (!$findFacultyLoadSchedule) {
                        $findFacultyLoad->faculty_load_schedules()->create([
                            'time_in' => $time_in,
                            'time_out' => $time_out,
                            'meridian' => $meridian,
                            'day_schedule_id' => $day_schedule_id,
                            'created_by' => auth()->user()->id,
                        ]);
                    }
                }
            }

            $ret = [
                "success" => true,
                "message" => "Excel Data Uploaded Successfully",
            ];
        } else {
            $ret = [
                "success" => false,
                "message" => "Excel Format is invalid"
            ];
        }

        $this->ret = $ret;
    }

    public function getMessage()
    {
        return $this->ret;
    }

    public function user_persmissions($user_id, $user_role_id)
    {
        if ($user_id != "" && $user_role_id != "") {
            $dataUserRolePermission = \App\Models\UserRolePermission::where('user_role_id', $user_role_id)
                ->get();

            foreach ($dataUserRolePermission as $key => $value) {
                $dataUserPermission = \App\Models\UserPermission::where('user_id', $user_id)
                    ->where('mod_button_id', $value->mod_button_id)
                    ->first();

                if ($dataUserPermission) {
                    $dataUserPermission->fill([
                        'status' => $value->status,
                        'updated_by' => auth()->user()->id
                    ])->save();
                } else {
                    \App\Models\UserPermission::create([
                        "user_id" => $user_id,
                        "mod_button_id" => $value->mod_button_id,
                        'status' => $value->status,
                        'created_by' => auth()->user()->id
                    ]);
                }
            }
        }
    }
}
