<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $deparment = "SELECT department_name FROM ref_departments WHERE ref_departments.id = schedules.department_id";
        $subject_code = "SELECT code FROM ref_subjects WHERE ref_subjects.id = schedules.subject_id";
        $section = "SELECT section FROM ref_sections WHERE ref_sections.id = schedules.section_id";
        $room_code = "SELECT room_code FROM ref_rooms WHERE ref_rooms.id = schedules.room_id";
        $max_slot = "SELECT max_slot FROM ref_rooms WHERE ref_rooms.id = schedules.room_id";
        $school_year = "SELECT CONCAT(`sy_from`, '-', `sy_to`) FROM ref_school_years WHERE ref_school_years.id = schedules.school_year_id";
        $semester = "SELECT semester FROM ref_semesters WHERE ref_semesters.id = schedules.semester_id";

        $data = Schedule::select([
            "*",
            DB::raw("($deparment) department"),
            DB::raw("($subject_code) subject_code"),
            DB::raw("($section) section"),
            DB::raw("($room_code) room_code"),
            DB::raw("($max_slot) max_slot"),
            DB::raw("($school_year) school_year"),
            DB::raw("($semester) semester"),
        ]);

        $data = $data->where(function ($query) use ($request, $deparment, $subject_code, $section, $room_code, $max_slot, $school_year, $semester) {
            if ($request->search) {
                $query->orWhere(DB::raw("($deparment)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($subject_code)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($section)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($room_code)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($max_slot)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($school_year)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($semester)"), 'LIKE', "%$request->search%");
            }
        });

        if ($request->student_id) {
            $data->where("student_id", $request->student_id);
        }

        if ($request->sort_field && $request->sort_order) {
            if (
                $request->sort_field != '' && $request->sort_field != 'undefined' && $request->sort_field != 'null'  &&
                $request->sort_order != ''  && $request->sort_order != 'undefined' && $request->sort_order != 'null'
            ) {
                $data = $data->orderBy(isset($request->sort_field) ? $request->sort_field : 'id', isset($request->sort_order)  ? $request->sort_order : 'desc');
            }
        } else {
            $data = $data->orderBy('id', 'desc');
        }

        if ($request->page_size) {
            $data = $data->paginate($request->page_size, ['*'], 'page', $request->page)->toArray();
        } else {
            $data = $data->get();
        }

        return response()->json([
            'success'   => true,
            'data'      => $data,
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
        // $ret  = [
        //     "success" => false,
        //     "message" => "Data not " . ($request->id ? "update" : "saved")
        // ];

        // $request->validate([
        //     'department_id' => ['required'],
        //     'subject_id' => ['required'],
        //     'section_id' => [
        //         'required',
        //         Rule::unique('schedules')->where(function ($query) use ($request) {
        //             return $query->where('section_id', $request->section_id)
        //                 ->where('subject_id', $request->subject_id);
        //         })->ignore($request->id)
        //     ],

        //     'room_id' => ['required'],

        //     'school_year_id' => ['required'],
        //     'semester_id' => ['required'],
        // ]);

        // $data = [
        //     "department_id" => $request->department_id,
        //     "subject_id" => $request->subject_id,
        //     "section_id" => $request->section_id,
        //     "room_id" => $request->room_id,

        //     "school_year_id" => $request->school_year_id,
        //     "semester_id" => $request->semester_id,
        // ];

        // $schedule = Schedule::updateOrCreate([
        //     "id" => $request->id,
        // ], $data);

        // if ($schedule) {
        //     $ret  = [
        //         "success" => true,
        //         "message" => "Data " . ($request->id ? "updated" : "saved") . " successfully"
        //     ];
        // }

        // return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret  = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findData = Schedule::find($id);

        if ($findData) {
            if ($findData->delete()) {
                $ret = [
                    "success" => true,
                    "message" => "Data deleted successfully"
                ];
            }
        }

        return response()->json($ret, 200);
    }
}