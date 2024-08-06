<?php

namespace App\Http\Controllers;

use App\Models\FacultyLoadSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultyLoadScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $deparment = "(SELECT (SELECT department_name FROM ref_departments WHERE ref_departments.id = faculty_loads.department_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $building = "(SELECT (SELECT building FROM ref_buildings WHERE ref_buildings.id = (SELECT ref_rooms.building_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id)) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $floor = "(SELECT (SELECT floor FROM ref_floors WHERE ref_floors.id = (SELECT ref_rooms.floor_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id)) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $room_code = "(SELECT (SELECT room_code FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $building_id = "(SELECT (SELECT building_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $floor_id = "(SELECT (SELECT floor_id FROM ref_rooms WHERE ref_rooms.id = faculty_loads.room_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $subject_code = "(SELECT (SELECT code FROM ref_subjects WHERE ref_subjects.id = faculty_loads.subject_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $school_year = "(SELECT (SELECT CONCAT(`sy_from`, '-', `sy_to`) FROM ref_school_years WHERE ref_school_years.id = faculty_loads.school_year_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $semester = "(SELECT (SELECT semester FROM ref_semesters WHERE ref_semesters.id = faculty_loads.semester_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $section = "(SELECT (SELECT section FROM ref_sections WHERE ref_sections.id = faculty_loads.section_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $faculty_no = "(SELECT (SELECT school_id FROM profiles WHERE profiles.id = faculty_loads.profile_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $fullname = "(SELECT (SELECT CONCAT(firstname, IF(lastname, CONCAT(' ', lastname), '')) FROM profiles WHERE profiles.id = faculty_loads.profile_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $day_schedule = "(SELECT (SELECT `name` FROM ref_day_schedules WHERE ref_day_schedules.id = faculty_load_schedules.day_schedule_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $gradeFileStatus = "(SELECT `status` FROM grade_files WHERE grade_files.faculty_load_id = faculty_loads.id ORDER BY grade_files.id DESC LIMIT 1)";
        $gradeFileStatusFaculty = "(SELECT (SELECT `status` FROM grade_files WHERE grade_files.faculty_load_id = faculty_loads.id ORDER BY grade_files.id DESC LIMIT 1) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $gradeFileRemarks = "(SELECT (SELECT `remarks` FROM grade_files WHERE grade_files.faculty_load_id = faculty_loads.id ORDER BY grade_files.id DESC LIMIT 1) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";
        $user_id = "(SELECT (SELECT (SELECT users.id FROM users WHERE users.id = profiles.user_id ORDER BY users.id DESC LIMIT 1) FROM profiles WHERE profiles.id = faculty_loads.profile_id ORDER BY faculty_loads.id DESC LIMIT 1) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)";

        $data = FacultyLoadSchedule::select([
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
            DB::raw("(SELECT IF((SELECT COUNT(*) FROM grade_files WHERE grade_files.faculty_load_id = faculty_loads.id) > 0, $gradeFileStatus, 'No Upload') FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id) gradeFileStatus"),
            DB::raw("$gradeFileRemarks gradeFileRemarks"),
        ]);


        $data->where(DB::raw("(SELECT (SELECT COUNT(*) FROM profiles WHERE profiles.id = faculty_loads.profile_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_schedules.faculty_load_id)"), ">", 0);

        if ($request->has("from")) {
            if ($request->from == 'page_monitoring') {
                $data->whereDoesntHave("faculty_load_monitorings", function ($query) {
                    $query->whereDate("created_at", "=", date('Y-m-d'));
                });

                // $data->where(DB::raw("( SELECT count(*) FROM faculty_load_monitorings WHERE faculty_load_id = faculty_loads.id AND DATE ( faculty_load_monitorings.created_at ) = DATE ( NOW()) )"), "=", 0);

                $data->where(DB::raw("( SELECT
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
                    // $data->orderByRaw("meridian, fullname ASC");
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
     * @param  \App\Models\FacultyLoadSchedule  $facultyLoadSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(FacultyLoadSchedule $facultyLoadSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FacultyLoadSchedule  $facultyLoadSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FacultyLoadSchedule $facultyLoadSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FacultyLoadSchedule  $facultyLoadSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacultyLoadSchedule $facultyLoadSchedule)
    {
        //
    }
}
