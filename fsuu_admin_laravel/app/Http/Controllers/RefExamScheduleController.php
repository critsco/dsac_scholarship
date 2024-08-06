<?php

namespace App\Http\Controllers;

use App\Models\RefExamSchedule;
use App\Models\StudentExam;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class RefExamScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $total_available_slot = "slots - (SELECT COUNT(*) FROM student_exams WHERE ref_exam_schedules.id=student_exams.exam_schedule_id AND schedule_status= 'Approved')";
        $applicants = "SELECT COUNT(*) FROM student_exams WHERE ref_exam_schedules.id=student_exams.exam_schedule_id AND schedule_status= 'Approved'";

        $data = RefExamSchedule::select([
            "*",
            DB::raw("($total_available_slot) as total_available_slot"),

            DB::raw("($applicants) as applicants"),
        ])->with(['ref_semester', 'student_exams']);

        if ($request->has('status')) {
            if (is_array($request->status)) {
                if (in_array('Active', $request->status) && in_array('Archived', $request->status)) {
                    $data->withTrashed();
                } else if (in_array('Active', $request->status)) {
                    $data->where('status', 'Active');
                } else if (in_array('Archived', $request->status)) {
                    $data->onlyTrashed();
                }
            } else {
                if ($request->status == 'Active') {
                    $data->whereNull('deleted_at');
                } else if ($request->status == 'Archived') {
                    $data->onlyTrashed();
                } else if ($request->status != 'Active' || $request->status != 'Archived') {
                    $data->withTrashed();
                } else if ($request->status == 'Active' || $request->status == 'Archived') {
                    $data->withTrashed();
                }
            }

            $data->where(function ($query) use ($request) {
                if ($request->search) {
                    $searchTerm = "%{$request->search}%";

                    $query->where('sy_from', 'LIKE', $searchTerm)
                        ->orWhere(DB::raw("DATE_FORMAT(exam_date, '%M' '%d' '%Y')"), 'LIKE', $searchTerm)
                        ->orwhere('time_in', 'LIKE', $searchTerm)
                        ->orWhereHas('ref_semester', function ($query) use ($searchTerm) {
                            $query->where('semester', 'LIKE', $searchTerm);
                        });
                }
            });
        }

        if ($request->has("sort_field") && $request->has("sort_order")) {
            if (
                $request->sort_field != '' && $request->sort_field != 'undefined' && $request->sort_field != 'null'  &&
                $request->sort_order != ''  && $request->sort_order != 'undefined' && $request->sort_order != 'null'
            ) {
                $data = $data->orderBy(isset($request->sort_field) ? $request->sort_field : 'id', isset($request->sort_order)  ? $request->sort_order : 'desc');
            }
        } else {
            $data = $data->orderBy('id', 'desc');
        }

        if ($request->has("page") && $request->has("page_size")) {
            $data = $data->limit($request->page_size)
                ->paginate($request->page_size, ['*'], 'page', $request->page)
                ->toArray();
        } else {
            $data = $data->get();
        }

        return response()->json([
            'success'   => true,
            'data'      => $data
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
        $ret  = [
            "success" => false,
            "message" => "Data not " . ($request->id ? "update" : "saved")
        ];

        $request->validate([
            'exam_date' => ['required'],
            'time_in' => [
                'required',
                Rule::unique('ref_exam_schedules')->where(function ($query) use ($request) {
                    return $query->where('sy_from', $request->sy_from)
                        ->where('sy_to', $request->sy_to)
                        ->where('semester_id', $request->semester_id)
                        ->where('time_in_meridiem', $request->time_in_meridiem)
                        ->where('time_out', $request->time_out)
                        ->where('time_out_meridiem', $request->time_out_meridiem)
                        ->where('exam_date', (new \DateTime($request->exam_date))->format('Y-m-d'))
                        ->where('deleted_at', null);
                })->ignore($request->id),
            ],
        ]);

        $data = [
            "sy_from" => $request->sy_from,
            "sy_to" => $request->sy_to,
            "semester_id" => $request->semester_id,
            'exam_date' => (new \DateTime($request->exam_date))->format('Y-m-d'),
            "time_in" => $request->time_in,
            "time_in_meridiem" => $request->time_in_meridiem,
            "time_out" => $request->time_out,
            "time_out_meridiem" => $request->time_out_meridiem,
            "slots" => $request->slots,
            "available_slots" => $request->slots,
            "status" => "Active",
        ];

        $findExamSchedule = RefExamSchedule::updateOrCreate([
            "id" => $request->id,
        ], $data);

        if ($findExamSchedule) {
            $ret  = [
                "success" => true,
                "message" => "Data " . ($request->id ? "updated" : "saved") . " successfully"
            ];
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RefExamSchedule  $refExamSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(RefExamSchedule $refExamSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefExamSchedule  $refExamSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefExamSchedule $refExamSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefExamSchedule  $refExamSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findData = RefExamSchedule::find($id);

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

    public function multiple_archived_exam_sched(Request $request)
    {
        $ret = [
            'success' => false,
            'message' => 'Data not archived!',
            'data' => $request->ids
        ];

        $applicantSchdule = StudentExam::whereIn('exam_schedule_id', $request->ids)->get();

        if ($applicantSchdule->count() > 0) {
            $ret = [
                'success' => false,
                'message' => 'Cannot archive exam schedule with scheduled applicants!',
                'data' => $request->ids
            ];
        } else {
            if ($request->has('ids') && count($request->ids) > 0) {
                foreach ($request->ids as $key => $value) {
                    $examSchedule = RefExamSchedule::find($value);

                    if ($examSchedule) {
                        if ($request->status == "Active") {
                            $examSchedule->fill([
                                'deleted_by' => auth()->user()->id,
                                'deleted_at' => now(),
                                'status' => 'Archived'
                            ])->save();
                        } else if ($request->status == "Archived") {
                            $examSchedule->fill([
                                'deleted_by' => NULL,
                                'updated_by' => NULL,
                                'status' => 'Active'
                            ])->save();
                        }
                    }
                }

                $ret = [
                    'success' => true,
                    'message' => 'Data ' . ($request->status == 'Active' ? 'archived' : 'activate') . ' successfully!',
                ];
            }
        }

        $ret += [
            "request" => $request->all()
        ];

        return response()->json($ret, 200);
    }

    public function exam_schedule_display()
    {
        $data = RefExamSchedule::select([
            "*"
        ])->orderBy('updated_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }
}
