<?php

namespace App\Http\Controllers;

use App\Imports\FacultyLoadImport;
use App\Models\FacultyLoad;
use App\Models\FacultyLoadMonitoring;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class FacultyLoadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $deparment = "(SELECT department_name FROM ref_departments WHERE ref_departments.id = faculty_loads.department_id)";
        $building = "(SELECT building FROM ref_buildings WHERE ref_buildings.id = (SELECT ref_rooms.building_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id))";
        $floor = "(SELECT floor FROM ref_floors WHERE ref_floors.id = (SELECT ref_rooms.floor_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id))";
        $room_code = "(SELECT room_code FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id)";
        $building_id = "(SELECT building_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id)";
        $floor_id = "(SELECT floor_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id)";
        $subject_code = "(SELECT code FROM ref_subjects WHERE ref_subjects.id = faculty_loads.subject_id)";
        $school_year = "(SELECT CONCAT(`sy_from`, '-', `sy_to`) FROM ref_school_years WHERE ref_school_years.id = faculty_loads.school_year_id)";
        $semester = "(SELECT semester FROM ref_semesters WHERE ref_semesters.id = faculty_loads.semester_id)";
        $section = "(SELECT section FROM ref_sections WHERE ref_sections.id = faculty_loads.section_id)";
        $faculty_no = "(SELECT school_id FROM profiles WHERE profiles.id = faculty_loads.profile_id)";
        $fullname = "(SELECT CONCAT(firstname, IF(lastname, CONCAT(' ', lastname), '')) FROM profiles WHERE profiles.id = faculty_loads.profile_id)";
        $gradeFileStatus = "(SELECT `status` FROM grade_files WHERE grade_files.faculty_load_id = faculty_loads.id ORDER BY grade_files.id DESC LIMIT 1)";
        $gradeFileRemarks = "(SELECT `remarks` FROM grade_files WHERE grade_files.faculty_load_id = faculty_loads.id ORDER BY grade_files.id DESC LIMIT 1)";
        $day_schedule = "(SELECT `name` FROM ref_day_schedules WHERE ref_day_schedules.id = faculty_loads.day_schedule_id)";
        $user_id = "(SELECT (SELECT users.id FROM users WHERE users.id = profiles.user_id ORDER BY users.id DESC LIMIT 1) FROM profiles WHERE profiles.id = faculty_loads.profile_id ORDER BY faculty_loads.id DESC LIMIT 1)";

        $data = FacultyLoad::select([
            "*",
            DB::raw("$building building"),
            DB::raw("$faculty_no faculty_no"),
            DB::raw("$building_id building_id"),
            DB::raw("$floor_id floor_id"),
            DB::raw("$floor floor"),
            DB::raw("$room_code room_code"),
            DB::raw("$semester semester"),
            DB::raw("$school_year school_year"),
            DB::raw("$subject_code code"),
            DB::raw("$section section"),
            DB::raw("$fullname fullname"),
            DB::raw("$deparment deparment"),
            DB::raw("$day_schedule day_schedule"),
            DB::raw("IF((SELECT COUNT(*) FROM grade_files WHERE grade_files.faculty_load_id = faculty_loads.id) > 0, $gradeFileStatus, 'No Upload') gradeFileStatus"),
            DB::raw("$gradeFileRemarks gradeFileRemarks"),
        ])
            ->with([
                'attachments',
                'grade_files' => function ($query) {
                    $query->with([
                        'attachments' => function ($query) {
                            $query->orderBy('id', 'desc');
                        }
                    ]);
                    $query->orderBy('id', 'desc');
                },
                'faculty_load_schedules' => function ($query) {
                    $day_schedule = "(SELECT `name` FROM ref_day_schedules WHERE ref_day_schedules.id = faculty_load_schedules.day_schedule_id)";

                    $query->select([
                        "*",
                        DB::raw("$day_schedule day_schedule"),
                    ]);
                },
                'profile' => function ($query) {
                    $query->with([
                        'attachments' => function ($query) {
                            $query->orderBy('id', 'desc');
                        }
                    ]);
                },
            ]);

        $data->where(DB::raw("(SELECT COUNT(*) FROM profiles WHERE profiles.id = faculty_loads.profile_id)"), ">", 0);

        $data->where(function ($query) use ($request, $building, $floor, $room_code, $subject_code, $school_year, $semester, $section, $fullname, $deparment,  $gradeFileStatus, $gradeFileRemarks, $faculty_no) {
            if ($request->has("search")) {
                $query->orWhere(DB::raw("$building"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("$floor"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("$room_code"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("$subject_code"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("$school_year"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("$semester"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("$section"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("$fullname"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("$deparment"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("$faculty_no"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("IF((SELECT COUNT(*) FROM grade_files WHERE grade_files.faculty_load_id = faculty_loads.id) > 0, $gradeFileStatus, 'No Upload')"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("$gradeFileRemarks"), 'LIKE', "%$request->search%");
            }
        });

        if ($request->has("from")) {
            if ($request->from == 'page_monitoring') {

                $data->whereDoesntHave("faculty_load_monitorings", function ($query) {
                    $query->whereDate("created_at", "=", date('Y-m-d'));

                    $query->whereHas('faculty_load_schedule', function ($query) {
                        $query->where(DB::raw("( SELECT
                                                    IF (
                                                        `name` = 'Monday',
                                                        'Mon',
                                                    IF
                                                        (
                                                            `name` = 'Tuesday',
                                                            'Tue',
                                                        IF
                                                            (
                                                                `name` = 'Wednesday',
                                                                'Wed',
                                                            IF
                                                                (
                                                                    `name` = 'Thursday',
                                                                    'Thu',
                                                                IF
                                                                    ( `name` = 'Friday', 'Fri', IF ( `name` = 'Saturday', 'Sat', IF ( `name` = 'Sunday', 'Sun', '' ) ) ) 
                                                                ) 
                                                            ) 
                                                        )) 
                                                FROM
                                                    ref_day_schedules 
                                                WHERE
                                                    ref_day_schedules.id = day_schedule_id 
                                                    ) = DATE_FORMAT(
                                                NOW(), '%a' )"), "=", 0);
                    });
                });
                // $data->where(DB::raw("( SELECT count(*) FROM faculty_load_monitorings WHERE faculty_load_id = faculty_loads.id AND DATE ( faculty_load_monitorings.created_at ) = DATE ( NOW()) )"), "=", 0);

                if ($request->has("building_id")) {
                    $data->where(DB::raw("(SELECT building_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id)"), $request->building_id);
                }

                if ($request->has("floor_id")) {
                    $data->where(DB::raw("(SELECT floor_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id)"), $request->floor_id);
                }

                if ($request->has("room_id")) {
                    $data->where(DB::raw("faculty_loads.room_id"), $request->room_id);
                }

                $data->whereHas('faculty_load_schedules', function ($query) {
                    $query->where(DB::raw("( SELECT
                                                IF (
                                                    `name` = 'Monday',
                                                    'Mon',
                                                IF
                                                    (
                                                        `name` = 'Tuesday',
                                                        'Tue',
                                                    IF
                                                        (
                                                            `name` = 'Wednesday',
                                                            'Wed',
                                                        IF
                                                            (
                                                                `name` = 'Thursday',
                                                                'Thu',
                                                            IF
                                                                ( `name` = 'Friday', 'Fri', IF ( `name` = 'Saturday', 'Sat', IF ( `name` = 'Sunday', 'Sun', '' ) ) ) 
                                                            ) 
                                                        ) 
                                                    )) 
                                            FROM
                                                ref_day_schedules 
                                            WHERE
                                                ref_day_schedules.id = day_schedule_id 
                                                ) = DATE_FORMAT(
                                            NOW(), '%a' )"), ">", 0);
                });
            }

            if ($request->from == 'page_faculty_load_report') {
                $data->where(DB::raw("( SELECT count(*) FROM faculty_load_monitorings WHERE faculty_load_id = faculty_loads.id AND DATE ( faculty_load_monitorings.created_at ) = DATE ( NOW()) )"), "=", 0);

                if ($request->has("building_id")) {
                    $data->where(DB::raw("(SELECT building_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id)"), $request->building_id);
                }

                if ($request->has("floor_id")) {
                    $data->where(DB::raw("(SELECT floor_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id)"), $request->floor_id);
                }

                if ($request->has("room_id")) {
                    $data->where(DB::raw("faculty_loads.room_id"), $request->room_id);
                }
            }

            if ($request->from == "grade_file") {
                if (auth()->user()->user_role_id == 3) {
                    $data->where(DB::raw("($user_id)"), auth()->user()->id);
                }

                if ($request->has("grade_file_status")) {
                    if ($request->grade_file_status == 'Approval') {
                        $data->whereIn(DB::raw("IF((SELECT COUNT(*) FROM grade_files WHERE grade_files.faculty_load_id = faculty_loads.id) > 0, ($gradeFileStatus), 'No Upload')"), ["Uploaded", "Incomplete"]);
                    } else if ($request->grade_file_status == 'Approved') {
                        $data->where(DB::raw("IF((SELECT COUNT(*) FROM grade_files WHERE grade_files.faculty_load_id = faculty_loads.id) > 0, ($gradeFileStatus), 'No Upload')"), "Approved");
                    }
                }
            }
        }

        if ($request->department_id) {
            $data->where('department_id', $request->department_id);
        }
        // else if ($request->department_id == null) {
        //     $data->withTrashed();
        // }


        if ($request->isTrash == 1) {
            $data->onlyTrashed();
        }

        // NOTE: GE CHANGE NAKO FROM STATUS TO IS_TRASH
        // if ($request->has('status')) {
        //     $status = explode(",", $request->status);
        //     if (in_array("Archived", $status) && in_array("Active", $status)) {
        //         $data->withTrashed();
        //     } else if (in_array("Archived", $status)) {
        //         $data->onlyTrashed();
        //     }
        // }

        if ($request->has("sort_field") && $request->has("sort_order")) {
            if (
                $request->sort_field != '' && $request->sort_field != 'undefined' && $request->sort_field != 'null'  &&
                $request->sort_order != ''  && $request->sort_order != 'undefined' && $request->sort_order != 'null'
            ) {
                $data->orderBy(isset($request->sort_field) ? $request->sort_field : 'id', isset($request->sort_order)  ? $request->sort_order : 'desc');
            }
        } else {
            if ($request->has("from")) {
                if ($request->from == 'page_monitoring') {
                    $data->orderByRaw("meridian, fullname ASC");
                } else {
                    $data->orderBy('id', 'desc');
                }
            } else {
                $data->orderBy('id', 'desc');
            }
        }

        if ($request->has("page") && $request->has("page_size")) {
            if ($request->calendar_view == 1) {
                $data = $data->get();
            } else {
                $data = $data->limit($request->page_size)
                    ->paginate($request->page_size, ['*'], 'page', $request->page)
                    ->toArray();

                $data["data"] = collect($data['data'])->map(function ($value) {
                    $value['grade_files'] = collect($value['grade_files'])->map(function ($value) {
                        $value['attachments'] = collect($value['attachments'])->map(function ($value) {
                            $pdf_file = base64_encode(file_get_contents($value['file_path']));

                            $value['pdf_file'] = "data:application/pdf;base64," . $pdf_file;
                            return $value;
                        });

                        return $value;
                    });

                    return $value;
                });
            }
        } else {
            $data = $data->get();
        }

        return response()->json([
            'success'   => true,
            'data'      => $data,
            'calendar_view' => $request->calendar_view
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FacultyLoad  $facultyLoad
     * @return \Illuminate\Http\Response
     */
    public function show(FacultyLoad $facultyLoad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FacultyLoad  $facultyLoad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FacultyLoad $facultyLoad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FacultyLoad  $facultyLoad
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacultyLoad $facultyLoad)
    {
    }

    public function multiple_archived(Request $request)
    {
        $ret = [
            'success' => false,
            'message' => 'Data not archived!',
        ];

        if ($request->has('ids') && count($request->ids) > 0) {
            foreach ($request->ids as $key => $value) {
                $facultyLoad = FacultyLoad::find($value);

                if ($facultyLoad) {
                    if ($request->isTrash == 0) {
                        $facultyLoad->fill([
                            'deleted_by' => auth()->user()->id,
                            'deleted_at' => now()
                        ])->save();
                    } else {
                        $facultyLoad->fill([
                            'deleted_by' => null,
                            'deleted_at' => null
                        ])->save();
                    }
                }
            }

            $ret = [
                'success' => true,
                'message' => 'Data ' . ($request->isTrash == 0 ? 'archived' : 'active') . ' successfully!',
            ];
        }

        return response()->json($ret, 200);
    }

    public function faculty_load_upload(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Excel Data Not Uploaded",
        ];

        if ($request->hasFile('file_excel')) {
            $path = $request->file('file_excel');

            $import = new FacultyLoadImport();
            Excel::import($import, $path);

            $ret = $import->getMessage();
        }

        return response()->json($ret, 200);
    }

    public function faculty_load_status(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Status not saved",
        ];

        $checFacultyLoadMonitoring = FacultyLoadMonitoring::where("faculty_load_id", $request->faculty_load_id)
            ->where("status_id", $request->status_id)
            ->whereDate("created_at", date('Y-m-d', strtotime($request->created_at)))
            ->count();

        if ($checFacultyLoadMonitoring == 0) {
            $createData = FacultyLoadMonitoring::create([
                "faculty_load_schedule_id" => $request->faculty_load_schedule_id,
                "faculty_load_id" => $request->faculty_load_id,
                "status_id" => $request->status_id,
                "remarks" => $request->remarks,
                "created_by" => auth()->user()->id,
                "created_at" => $request->created_at,
                "updated_at" => $request->created_at,
            ]);

            if ($createData) {
                if ($request->fileCounter > 0) {
                    for ($x = 0; $x < $request->fileCounter; $x++) {
                        if ($request->hasFile('file_' . $x)) {
                            $imageFile = $request->file('file_' . $x);

                            $this->create_attachment($createData, $imageFile, [
                                "action" => "Add",
                                "folder_name" => "faculty_load_mornitoring/faculty_load_mornitoring-" . $createData->id,
                                "file_description" => "Faculty Load Mornitoring",
                                "file_type" => "image",
                            ]);
                        }
                    }
                }

                $facultyLoad = FacultyLoad::with(['profile'])->find($request->faculty_load_id);

                $firstname = "";
                $lastname = "";
                if ($facultyLoad) {
                    $firstname = $facultyLoad->profile->firstname;
                    $lastname = $facultyLoad->profile->lastname;
                }

                $name = "";
                $findUserProfile = Profile::find(auth()->user()->id);
                if ($findUserProfile) {
                    if ($findUserProfile->firstname) {
                        $name .= $findUserProfile->firstname;
                    }
                    if ($findUserProfile->lastname) {
                        $name .= " " . $findUserProfile->lastname;
                    }
                }

                $users = User::whereIn("user_role_id", [1, 3])->pluck("id")->toArray();

                $link = "";
                if ($request->status_id == 1) {
                    $link = "/faculty-load/present-list";
                } else {
                    $link = "/faculty-load/absent-list";
                }

                $this->send_notification([
                    "title" => "New Monitoring Report",
                    "description" => "New monitoring report has been uploaded by " . $name . " for " . $firstname . " " . $lastname . " on " . date('F d, Y', strtotime($createData->created_at)) . " at " . date('h:i A', strtotime($createData->created_at)) . ".",
                    "link" => $link,
                    "link_origin" => $request->link_origin,
                    "userIds" => $users,
                    "system_id" => 2,
                ]);

                $ret = [
                    "success" => true,
                    "message" => "Status saved successfully",
                    "auth" => auth()->user(),
                    "PUSHER_APP_ID" => env('PUSHER_APP_ID'),
                ];
            }
        } else {
            $ret = [
                "success" => false,
                "message" => "Status already saved"
            ];
        }

        return response()->json($ret, 200);
    }

    public function faculty_load_status_bulk(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Status not saved",
        ];

        if (count($request->data) > 0) {
            foreach ($request->data as $key => $value) {
                $checFacultyLoadMonitoring = FacultyLoadMonitoring::where("faculty_load_id", $value['faculty_load_id'])
                    ->where("status_id", $value['status_id'])
                    ->whereDate("created_at", date('Y-m-d', strtotime($value['created_at'])))
                    ->count();

                if ($checFacultyLoadMonitoring == 0) {
                    $dataFacultyLoadMonitoring = [
                        "status_id" => $value['status_id'],
                        "remarks" => $value['remarks'],
                        "faculty_load_id" => $value['faculty_load_id'],
                        "created_at" => $value['created_at'],
                        "updated_at" => $value['updated_at'],
                        "created_by" => $value['created_by'],
                    ];

                    $createData = FacultyLoadMonitoring::create($dataFacultyLoadMonitoring);

                    if ($createData) {
                        $folder_name = Str::random(10);

                        if (!Storage::exists("uploads/attachments/facultyload/$folder_name")) {
                            Storage::makeDirectory("uploads/attachments/facultyload/$folder_name");
                        }

                        $attachments = json_decode($value['fileList'], true);
                        foreach ($attachments as $key => $value) {
                            $name = $value['name'];
                            $size = $value['size'];

                            $uploadSettings = ["directory" => "uploads/attachments/facultyload/$folder_name", "disk" => "public"];
                            $file_uploader_factory = new \OldRavian\FileUploader\Factories\FileUploaderFactory();
                            $file_uploader = $file_uploader_factory->build("base64");

                            //first parameter should be an instance of \Illuminate\Http\UploadedFile
                            //second parameter is optional, if you leave that parameter then default settings will be used
                            $data = $file_uploader->upload($value["base64"], $uploadSettings); //it will return an array

                            $createData->attachments()->create([
                                'file_name' => $name,
                                'file_path' => "storage/uploads/attachments/facultyload/$folder_name/" . $data['filename'],
                                'file_size' => $size,
                                'folder_name' => $folder_name,
                                'file_description' => "Faculty Load Mornitoring Report"
                            ]);
                        }

                        $facultyLoad = FacultyLoad::with(['profile'])->find($value['faculty_load_id']);

                        $firstname = "";
                        $lastname = "";
                        if ($facultyLoad) {
                            $firstname = $facultyLoad->profile->firstname;
                            $lastname = $facultyLoad->profile->lastname;
                        }

                        $name = "";
                        $findUserProfile = Profile::find(auth()->user()->id);
                        if ($findUserProfile) {
                            if ($findUserProfile->firstname) {
                                $name .= $findUserProfile->firstname;
                            }
                            if ($findUserProfile->lastname) {
                                $name .= " " . $findUserProfile->lastname;
                            }
                        }

                        $link = "";
                        if ($value['status_id'] == 1) {
                            $link = "/faculty-load/present-list";
                        } else {
                            $link = "/faculty-load/absent-list";
                        }

                        $users = User::whereIn("user_role_id", [1, 3])->pluck("id")->toArray();

                        $this->send_notification([
                            "title" => "New Monitoring Report",
                            "description" => "New monitoring report has been uploaded by " . $name . " for " . $firstname . " " . $lastname . " on " . date('F d, Y', strtotime($createData->created_at)) . " at " . date('h:i A', strtotime($createData->created_at)) . ".",
                            "link" => $link,
                            "link_origin" => $request->link_origin,
                            "userIds" => $users,
                            "system_id" => 2,
                        ]);
                    }
                }
            }

            $ret = [
                "success" => true,
                "message" => "Bulk data saved successfully",
            ];
        }

        return response()->json($ret, 200);
    }

    public function faculty_load_update_room(Request $request)
    {
        $ret  = [
            "success" => true,
            "message" => "Room not updated",
        ];

        $data = [
            "room_id" => $request->room_id,
        ];

        $room = FacultyLoad::find($request->id);

        if ($room) {
            $room = $room->fill($data);
            if ($room->save()) {
                $ret  = [
                    "success" => true,
                    "message" => "Data updated successfully"
                ];
            }
        }

        return response()->json($ret, 200);
    }

    public function faculty_load_report_print(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not found",
            "request" => $request->all()
        ];

        return response()->json($ret, 200);
    }

    public function grade_file_approval(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not approved"
        ];

        if ($request->ids && count($request->ids) > 0) {
            foreach ($request->ids as $key => $value) {
            }
        }
    }
}
