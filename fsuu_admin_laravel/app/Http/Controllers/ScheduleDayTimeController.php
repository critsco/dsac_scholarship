<?php

namespace App\Http\Controllers;


use App\Models\ScheduleDayTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ScheduleDayTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $department = "SELECT department_name FROM ref_departments WHERE ref_departments.id = ( SELECT department_id FROM schedules WHERE schedules.id = schedule_day_times.schedule_id )";
        $section = "SELECT section FROM ref_sections WHERE ref_sections.id = (SELECT section_id FROM schedules WHERE schedules.id = schedule_day_times.schedule_id )";
        $subject = "SELECT code FROM ref_subjects WHERE ref_subjects.id = (SELECT subject_id FROM schedules WHERE schedules.id = schedule_day_times.schedule_id)";
        $room = "SELECT room_code FROM ref_rooms WHERE ref_rooms.id = (SELECT room_id FROM schedules WHERE schedules.id = schedule_day_times.schedule_id )";
        $max_slot = "SELECT max_slot FROM ref_rooms WHERE ref_rooms.id = (SELECT room_id FROM schedules WHERE schedules.id = schedule_day_times.schedule_id )";
        $day = "SELECT `name` FROM ref_day_schedules WHERE ref_day_schedules.id = schedule_day_times.day_id ";
        $time = "SELECT CONCAT(time_in, ' ',time_in_meridiem, ' - ', time_out , ' ', time_out_meridiem) FROM ref_time_schedules WHERE ref_time_schedules.id = schedule_day_times.time_id ";
        $semester = "SELECT semester FROM ref_semesters WHERE ref_semesters.id = (SELECT semester_id FROM schedules WHERE schedules.id = schedule_day_times.schedule_id )";
        $school_year = "SELECT CONCAT(`sy_from`, '-', `sy_to`) FROM ref_school_years WHERE id = (SELECT school_year_id FROM schedules WHERE schedules.id = schedule_day_times.schedule_id ) ";

        $data = ScheduleDayTime::select([
            "*",
            DB::raw("($department) department"),
            DB::raw("($section) section"),
            DB::raw("($subject) subject"),
            DB::raw("($room) room"),
            DB::raw("($max_slot) max_slot"),
            DB::raw("($day) day"),
            DB::raw("($time) time"),
            DB::raw("($semester) semester"),
            DB::raw("($school_year) school_year"),
        ]);

        $data = $data->where(function ($query) use ($request, $department, $section, $subject, $room, $day, $time, $semester, $school_year, $max_slot) {
            if ($request->search) {
                $query->orWhere(DB::raw("($department)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($section)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($subject)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($room)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($max_slot)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($day)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($time)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($semester)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($school_year)"), 'LIKE', "%$request->search%");
            }
        });

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
            $data = $data->limit($request->page_size)
                ->paginate($request->page_size, ['*'], 'page', $request->page)
                ->toArray();
        } else {
            $data = $data->get();
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
        //     'schedule_id' => [
        //         'required',
        //         Rule::unique('schedule_day_times')->where(function ($query) use ($request) {
        //             return $query->where('day_id', $request->day_id)
        //                 ->where('time_id', $request->time_id)->where('room_id', $request->room_id);
        //         })->ignore($request->id)
        //     ],
        //     'day_id' => ['required'],
        //     'time_id' => ['required'],
        // ]);

        // $data = [
        //     "schedule_id" => $request->schedule_id,
        //     "day_id" => $request->day_id,
        //     "time_id" => $request->time_id,
        // ];

        // $schedule = ScheduleDayTime::updateOrCreate([
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
     * @param  \App\Models\ScheduleDayTime  $scheduleDayTime
     * @return \Illuminate\Http\Response
     */
    public function show(ScheduleDayTime $scheduleDayTime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScheduleDayTime  $scheduleDayTime
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScheduleDayTime $scheduleDayTime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScheduleDayTime  $scheduleDayTime
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $ret  = [
        //     "success" => false,
        //     "message" => "Data not deleted"
        // ];

        // $findData = ScheduleDayTime::find($id);

        // if ($findData) {
        //     if ($findData->delete()) {
        //         $ret = [
        //             "success" => true,
        //             "message" => "Data deleted successfully"
        //         ];
        //     }
        // }

        // return response()->json($ret, 200);
    }
}
