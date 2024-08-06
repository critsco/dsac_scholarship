<?php

namespace App\Imports;

use App\Models\Profile;
use App\Models\RefSection;
use App\Models\RefSubject;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentSubjectImport implements ToCollection
{
    private $ret = [];
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $ret = [
            "success" => false,
            "message" => "Excel Data Not Uploaded",
        ];

        $school_year_id = $this->data['school_year_id'];
        $semester_id = $this->data['semester_id'];

        $header = ["", "", "", "", "", "", "", "", "", "", ""];
        $data = [];
        $lastNonEmptyValues = ["", "", "", "", ""];

        foreach ($collection as $key => $value) {
            if ($key == 0) {
                $col0  = strtoupper(str_replace(' ', '', isset($value[0]) ? $value[0] : null));
                $col1  = strtoupper(str_replace(' ', '', isset($value[1]) ? $value[1] : null));
                $col2  = strtoupper(str_replace(' ', '', isset($value[2]) ? $value[2] : null));
                $col3  = strtoupper(str_replace(' ', '', isset($value[3]) ? $value[3] : null));
                $col4  = strtoupper(str_replace(' ', '', isset($value[4]) ? $value[4] : null));
                $col5  = strtoupper(str_replace(' ', '', isset($value[5]) ? $value[5] : null));
                $col6  = strtoupper(str_replace(' ', '', isset($value[6]) ? $value[6] : null));
                $col7  = strtoupper(str_replace(' ', '', isset($value[7]) ? $value[7] : null));

                $header = [
                    $col0,
                    $col1,
                    $col2,
                    $col3,
                    $col4,
                    $col5,
                    $col6,
                    $col7,
                ];
            }

            if ($key >= 2) {
                $col0  = mb_convert_encoding(isset($value[0]) ? trim($value[0]) : null, 'UTF-8', 'auto');
                $col1  = mb_convert_encoding(isset($value[1]) ? trim($value[1]) : null, 'UTF-8', 'auto');
                $col2  = mb_convert_encoding(isset($value[2]) ? trim($value[2]) : null, 'UTF-8', 'auto');
                $col3  = mb_convert_encoding(isset($value[3]) ? trim($value[3]) : null, 'UTF-8', 'auto');
                $col4  = mb_convert_encoding(isset($value[4]) ? trim($value[4]) : null, 'UTF-8', 'auto');
                $col5  = mb_convert_encoding(isset($value[5]) ? trim($value[5]) : null, 'UTF-8', 'auto');
                $col6  = mb_convert_encoding(isset($value[6]) ? trim($value[6]) : null, 'UTF-8', 'auto');
                $col7  = mb_convert_encoding(isset($value[7]) ? trim($value[7]) : null, 'UTF-8', 'auto');

                if ($col0 == "" && $col1 == "") {
                    $col0 = $lastNonEmptyValues[0];
                    $col1 = $lastNonEmptyValues[1];
                    $col2 = $lastNonEmptyValues[2];
                    $col3 = $lastNonEmptyValues[3];
                    $col4 = $lastNonEmptyValues[4];
                } else {
                    $lastNonEmptyValues[0] = $col0;
                    $lastNonEmptyValues[1] = $col1;
                    $lastNonEmptyValues[2] = $col2;
                    $lastNonEmptyValues[3] = $col3;
                    $lastNonEmptyValues[4] = $col4;
                }

                $data[] = [
                    $col0,
                    $col1,
                    $col2,
                    $col3,
                    $col4,
                    $col5,
                    $col6,
                    $col7,
                ];
            }
        }

        $ifHeader = $header[0] == "STUDENTNUMBER" && $header[1] == "EMAILADDRESS" && $header[2] == "LASTNAME" && $header[3] == "FIRSTNAME" && $header[4] == "MIDDLENAME" && $header[5] == "SUBJECTCODE" && $header[6] == "SUBJECTSECTION" && $header[7] == "DESCRIPTION";

        if ($ifHeader) {
            foreach ($data as $key => $value) {
                $studentNumber = $value[0];
                $emailAddress = $value[1];
                $lastName = $value[2];
                $firstName = $value[3];
                $middleName = $value[4];
                $subjectCode = $value[5];
                $subjectSection = $value[6];
                $description = $value[7];

                $profile_id = null;

                $findProfile = Profile::where("school_id", $studentNumber)->first();

                if ($findProfile) {
                    $profile_id = $findProfile->id;
                } else {
                    $findUserEmail = User::where("email", $emailAddress)->first();
                    $findUserName = User::where("username", $studentNumber)->first();

                    $user_id = null;

                    if ($findUserEmail && $findUserName) {
                        $user_id = $findUserEmail->id;
                    } else if ($findUserEmail && !$findUserName) {
                        $user_id = $findUserEmail->id;
                        $findUserEmail->update([
                            "username" => $studentNumber,
                        ]);
                    } else if ($findUserName && !$findUserEmail) {
                        $user_id = $findUserName->id;
                        $findUserName->update([
                            "email" => $emailAddress,
                        ]);
                    } else {
                        $createUser = User::create([
                            "username" => $studentNumber,
                            "email" => $emailAddress,
                            "email_verified_at" => now(),
                            "password" => Hash::make("Admin123!"),
                            "user_role_id" => 4,
                            "status" => "Active",
                            "remember_token" => Str::random(10),
                            "created_by" => auth()->user()->id,
                        ]);

                        if ($createUser) {
                            $user_id = $createUser->id;
                        }
                    }

                    $createProfile = Profile::create([
                        "user_id" => $user_id,
                        "school_id" => $studentNumber,
                        "lastname" => $lastName,
                        "firstname" => $firstName,
                        "middlename" => $middleName,
                        "created_by" => auth()->user()->id,
                    ]);

                    if ($createProfile) {
                        $profile_id = $createProfile->id;
                    }
                }

                if ($profile_id) {
                    $subject_id = null;

                    $findSubject = RefSubject::where("code", $subjectCode)->first();

                    if ($findSubject) {
                        $subject_id = $findSubject->id;
                    } else {
                        $createSubject = RefSubject::create([
                            "code" => $subjectCode,
                            "name" => $description,
                            "created_by" => auth()->user()->id,
                        ]);

                        if ($createSubject) {
                            $subject_id = $createSubject->id;
                        }
                    }

                    $section_id = null;

                    $findSection = RefSection::where("section", $subjectSection)->first();

                    if ($findSection) {
                        $section_id = $findSection->id;
                    } else {
                        $createSection = RefSection::create([
                            "section" => $subjectSection,
                            "created_by" => auth()->user()->id,
                        ]);

                        if ($createSection) {
                            $section_id = $createSection->id;
                        }
                    }

                    $findStudentSubject = Schedule::where("student_id", $profile_id)
                        ->where("subject_id", $subject_id)
                        ->where("section_id", $section_id)
                        ->where("school_year_id", $school_year_id)
                        ->where("semester_id", $semester_id)
                        ->first();

                    if (!$findStudentSubject) {
                        Schedule::create([
                            "student_id" => $profile_id,
                            "subject_id" => $subject_id,
                            "section_id" => $section_id,
                            "school_year_id" => $school_year_id,
                            "semester_id" => $semester_id,
                            "created_by" => auth()->user()->id,
                        ]);
                    }
                }
            }
        }

        $ret = [
            "success" => true,
            "message" => "Excel Data Uploaded",
            "header" => $header,
            "data" => $data,
            "thos" => $this->data
        ];

        $this->ret = $ret;
    }

    public function getMessage()
    {
        return $this->ret;
    }
}
