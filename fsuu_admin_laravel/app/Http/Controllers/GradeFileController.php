<?php

namespace App\Http\Controllers;

use App\Models\GradeFile;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $class_time = "SELECT CONCAT(time_in, '-', time_out, ' ', meridian) FROM faculty_loads WHERE faculty_loads.id = grade_files.faculty_load_id";
        $fullname = "SELECT (SELECT CONCAT(firstname, IF(lastname, CONCAT(' ', lastname), '')) FROM profiles WHERE profiles.id = faculty_loads.profile_id) FROM faculty_loads WHERE faculty_loads.id = grade_files.faculty_load_id";
        $room_code = "SELECT (SELECT room_code FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id) FROM faculty_loads WHERE faculty_loads.id = grade_files.faculty_load_id";
        $subject_code = "SELECT (SELECT code FROM ref_subjects WHERE ref_subjects.id = faculty_loads.subject_id) FROM faculty_loads WHERE faculty_loads.id = grade_files.faculty_load_id";
        $school_year = "SELECT (SELECT CONCAT(`sy_from`, '-', `sy_to`) FROM ref_school_years WHERE ref_school_years.id = faculty_loads.school_year_id) FROM faculty_loads WHERE faculty_loads.id = grade_files.faculty_load_id";
        $semester = "SELECT (SELECT semester FROM ref_semesters WHERE ref_semesters.id = faculty_loads.semester_id) FROM faculty_loads WHERE faculty_loads.id = grade_files.faculty_load_id";
        $section = "SELECT (SELECT section FROM ref_sections WHERE ref_sections.id = faculty_loads.section_id) FROM faculty_loads WHERE faculty_loads.id = grade_files.faculty_load_id";

        $data = GradeFile::select([
            "*",
            DB::raw("($class_time) class_time"),
            DB::raw("($fullname) fullname"),
            DB::raw("($room_code) room_code"),
            DB::raw("($subject_code) code"),
            DB::raw("($school_year) school_year"),
            DB::raw("($semester) semester"),
            DB::raw("($section) section"),
        ])
            ->with([
                "attachments" => function ($query) {
                    $query->orderBy("id", "desc");
                },
                "faculty_load.profile.attachments",
            ]);

        if ($request->status) {
            if ($request->status == "Approval") {
                $data->where("status", "Uploaded");
            } else {
                $data->where("status", $request->status);
            }
        }

        $user_role = \App\Models\UserRole::find(auth()->user()->user_role_id);

        if ($user_role) {
            if ($user_role->role === "Faculty Admin") {

                $data->whereHas("faculty_load", function ($query) {
                    $profile_id = null;
                    $profile = \App\Models\Profile::firstWhere("user_id", auth()->user()->id);
                    if ($profile) {
                        $profile_id = $profile->id;
                    }
                    $query->where("profile_id", $profile_id);
                });
            }
        }

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
            $data = $data->limit($request->page_size)
                ->paginate($request->page_size, ['*'], 'page', $request->page)
                ->toArray();

            $data["data"] = collect($data['data'])->map(function ($value) {
                $value['attachments'] = collect($value['attachments'])->map(function ($value) {
                    $pdf_file = base64_encode(file_get_contents($value['file_path']));

                    $value['pdf_file'] = "data:application/pdf;base64," . $pdf_file;
                    return $value;
                });

                return $value;
            });
        } else {
            $data = $data->get();
        }

        return response()->json([
            'success'   => true,
            'data'      => $data,
            "auth" => auth()->user(),
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
        $ret = [
            "success" => false,
            "message" => "Grade File Not Uploaded",
        ];

        if ($request->hasFile('file')) {
            $findGradeFile = GradeFile::where("faculty_load_id", $request->faculty_load_id)->first();

            $data = [
                "faculty_load_id" => $request->faculty_load_id,
                "description" => $request->description,
                "type" => $request->type,
                "signature_status" => $request->signature_status,
            ];

            if (!$findGradeFile) {
                $data["status"] = "Uploaded";
            }

            $queryGradeFile = GradeFile::updateOrCreate(
                [
                    "id" => $findGradeFile->id ?? null,
                ],
                $data
            );

            if ($queryGradeFile) {
                $this->create_attachment($queryGradeFile, $request->file('file'), [
                    "action" => "Add",
                    "folder_name" => "grade_files/grade_file-" . $queryGradeFile->id,
                    "file_description" => "Grade File",
                    "file_type" => "document",
                ]);

                $ret = [
                    "success" => true,
                    "message" => "Grade File Uploaded",
                ];
            }
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GradeFile  $gradeFile
     * @return \Illuminate\Http\Response
     */
    public function show(GradeFile $gradeFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GradeFile  $gradeFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GradeFile $gradeFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GradeFile  $gradeFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(GradeFile $gradeFile)
    {
        //
    }

    public function grade_file_status(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Grade File Status Not Updated",
        ];

        if ($request->ids && count($request->ids) > 0) {
            foreach ($request->ids as $key => $value) {
                $gradeFile = GradeFile::find($value);

                if ($gradeFile) {
                    $gradeFile->status = $request->status;
                    $gradeFile->remarks = $request->remarks;
                    $gradeFile->save();
                }
            }

            $ret = [
                "success" => true,
                "message" => "Grade File Status Updated",
            ];
        }



        return response()->json($ret, 200);
    }

    public function grade_submision_graph(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "No data"
        ];

        if ($request->action == 'all') {
            $ret = [
                "success" => true,
                "data" => $this->gradeFileSubmissionAll($request)
            ];
        }

        return response()->json($ret, 200);
    }

    private function gradeFileSubmissionAll($request)
    {
        $data_series_name   = [];
        $data_series_value  = [];

        $fullname = "TRIM(CONCAT_WS(' ', firstname, IF(middlename='', NULL, middlename), lastname, IF(name_ext='', NULL, name_ext)))";
        $dataProfile = Profile::select([
            "*",
            DB::raw("$fullname fullname"),
        ])
            ->whereHas("faculty_loads", function ($query) {
                $query->whereHas("grade_files", function ($query) {
                    $query->whereIn("status", ["Uploaded", "Approved"]);
                });
            })
            ->get();

        foreach ($dataProfile as $key => $value) {
            $data_series_name[] = $value->fullname;

            $data_value = 0;

            $data_series_value[] = [
                "name" => $value->fullname,
                "data" => [$data_value]
            ];
        }

        $data = [
            "data_series_name"  => $data_series_name,
            "data_series_value" => $data_series_value,
            "dataProfile"       => $dataProfile,
            "action"            => "all",
            "downTo"            => "all",
        ];

        return $data;
    }
}