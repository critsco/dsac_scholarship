<?php

namespace App\Http\Controllers;

use App\Models\FacultyLoadMonitoring;
use App\Models\FacultyLoadMonitoringJustification;
use App\Models\RefStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacultyLoadMonitoringJustificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = "SELECT `status` FROM ref_statuses WHERE ref_statuses.id = faculty_load_monitoring_justifications.status_id";
        $fullname = "SELECT CONCAT(firstname, IF(lastname, CONCAT(' ', lastname), '')) FROM profiles WHERE profiles.id = (SELECT profile_id FROM faculty_loads WHERE faculty_loads.id = (SELECT faculty_load_id FROM faculty_load_monitorings WHERE faculty_load_monitorings.id = faculty_load_monitoring_justifications.faculty_load_monitoring_id))";
        $approved_by_name = "SELECT CONCAT(firstname, IF(lastname, CONCAT(' ', lastname), '')) FROM profiles WHERE profiles.user_id = (SELECT id FROM users WHERE users.id = faculty_load_monitoring_justifications.approved_by)";
        $remaks_new = "CONCAT(remarks, IF(remarks IS NOT NULL, CONCAT(' / ', remarks2), ''))";
        $date_reported = "SELECT DATE_FORMAT(faculty_load_monitorings.created_at, '%m/%d/%Y') FROM faculty_load_monitorings WHERE faculty_load_monitorings.id = faculty_load_monitoring_id ORDER BY faculty_load_monitorings.id DESC LIMIT 1";
        $time = "SELECT CONCAT(time_in,'-', time_out, ' ', meridian) FROM faculty_load_schedules WHERE faculty_load_schedules.id = (SELECT faculty_load_schedule_id FROM faculty_load_monitorings WHERE faculty_load_monitorings.id = faculty_load_monitoring_id ORDER BY faculty_load_monitorings.id DESC LIMIT 1)";

        $data = FacultyLoadMonitoringJustification::select([
            "*",
            DB::raw("($status) status"),
            DB::raw("($fullname) fullname"),
            DB::raw("($approved_by_name) approved_by_name"),
            DB::raw("DATE_FORMAT(date_approved, '%m/%d/%Y') date_approved_format"),
            DB::raw("($remaks_new) remaks_new"),
            DB::raw("($date_reported) date_reported"),
            DB::raw("($time) time"),
        ])->with("attachments");

        $data->where(function ($query) use ($request, $fullname, $status, $approved_by_name, $remaks_new, $date_reported, $time) {
            if ($request->search) {
                $query->orWhere(DB::raw("($fullname)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($status)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($approved_by_name)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("DATE_FORMAT(date_approved, '%m/%d/%Y')"), 'LIKE', "%$request->search%");
                $query->orWhere("remarks", 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($remaks_new)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($date_reported)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($time)"), 'LIKE', "%$request->search%");
            }
        });

        if ($request->status_id) {
            $data->where("status_id", $request->status_id);
        }
        if ($request->department_id) {
            $data->where(DB::raw("(SELECT department_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_id)"), $request->department_id);
        }

        if ($request->from) {
            if ($request->from == 'page_monitoring') {
                $data->where(DB::raw("( SELECT count(*) FROM faculty_load_monitorings WHERE faculty_load_id = faculty_loads.id AND DATE ( faculty_load_monitorings.created_at ) = DATE ( NOW()) )"), "=", 0);
            }
        }

        if ($request->date_range_format) {
            $date_range_format = explode(",", $request->date_range_format);
            $date_from = date("Y-m-d", strtotime($date_range_format[0]));
            $date_to = date("Y-m-d", strtotime($date_range_format[1]));

            $data->where(DB::raw("DATE_FORMAT(faculty_load_monitorings.created_at, '%Y-%m-%d')"), '>=', $date_from);
            $data->where(DB::raw("DATE_FORMAT(faculty_load_monitorings.created_at, '%Y-%m-%d')"), '<=', $date_to);
        }

        if ($request->sort_field && $request->sort_order) {
            if (
                $request->sort_field != '' && $request->sort_field != 'undefined' && $request->sort_field != 'null'  &&
                $request->sort_order != ''  && $request->sort_order != 'undefined' && $request->sort_order != 'null'
            ) {
                $data->orderBy(isset($request->sort_field) ? $request->sort_field : 'id', isset($request->sort_order)  ? $request->sort_order : 'desc');
            }
        } else {
            $data->orderBy('id', 'desc');
        }

        if ($request->page_size) {
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FacultyLoadMonitoringJustification  $facultyLoadMonitoringJustification
     * @return \Illuminate\Http\Response
     */
    public function show(FacultyLoadMonitoringJustification $facultyLoadMonitoringJustification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FacultyLoadMonitoringJustification  $facultyLoadMonitoringJustification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FacultyLoadMonitoringJustification $facultyLoadMonitoringJustification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FacultyLoadMonitoringJustification  $facultyLoadMonitoringJustification
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacultyLoadMonitoringJustification $facultyLoadMonitoringJustification)
    {
        //
    }

    public function flm_endorse_for_approval(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Justification not " . ($request->id ? "updated" : "saved"),
            "request" => $request->all()
        ];

        $data = [
            "faculty_load_monitoring_id" => $request->faculty_load_monitoring_id,
            "remarks" => $request->remarks,
        ];

        if ($request->id) {
            $data += [
                "updated_by" => auth()->user()->id
            ];
        } else {
            $findStatusForApproval = RefStatus::firstWhere("code", "S-07");
            $data += [
                "status_id" => $findStatusForApproval->id,
                "created_by" => auth()->user()->id
            ];
        }

        $dataFacultyLoadMonitoringJustification = FacultyLoadMonitoringJustification::updateOrCreate([
            "id" => $request->id,
        ], $data);

        if ($dataFacultyLoadMonitoringJustification) {
            if ($request->fileCounter > 0) {
                for ($x = 0; $x < $request->fileCounter; $x++) {
                    if ($request->hasFile('file_' . $x)) {
                        $imageFile = $request->file('file_' . $x);

                        $this->create_attachment($dataFacultyLoadMonitoringJustification, $imageFile,  [
                            'folder_name' => "justifications/justification-$dataFacultyLoadMonitoringJustification->id",
                            'file_description' => "Faculty Load Mornitoring Endorse For Approval",
                            'file_type' => 'image'
                        ]);
                    }
                }
            }

            // $findStatus = RefStatus::find($dataFacultyLoadMonitoringJustification->status_id);

            // $findFacultyLoadMonitoring = FacultyLoadMonitoring::with(["faculty_load.profile.user"])->find($dataFacultyLoadMonitoringJustification->faculty_load_monitoring_id);

            // if ($findFacultyLoadMonitoring) {
            //     $toname = $findFacultyLoadMonitoring->faculty_load->profile->firstname . " " . $findFacultyLoadMonitoring->faculty_load->profile->lastname;
            //     $toemail = $findFacultyLoadMonitoring->faculty_load->profile->user->email;

            //     $this->send_email([
            //         "title" => "FACULTY LOAD MONITORING ENDORSE",
            //         "system_id" => 2,
            //         "to_name" => $toname,
            //         "to_email" => $toemail,
            //     ]);

            //     $this->send_notification([
            //         "system_id" => 2,
            //         "title" => "Faculty Load Monitoring Endorse",
            //         "description" => "Justification ".,
            //     ]);
            // }


            $ret = [
                "success" => true,
                "message" => "Justification " . ($request->id ? "updated" : "saved") . " successfully"
            ];
        }

        return response()->json($ret, 200);
    }

    public function flm_justification_update_status(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Justification not " . ($request->id ? "updated" : "saved")
        ];

        $data = [
            "status_id" => $request->status_id,
            "remarks2" => $request->remarks2,

        ];

        $findStatus = RefStatus::find($request->status_id);

        if ($findStatus) {
            if (in_array($findStatus->code, ["S-08", "S-09"])) {
                $data += [
                    "approved_by" => auth()->user()->id,
                    "date_approved" => now()
                ];
            }
        }

        if ($request->id) {
            $data += [
                "updated_by" => auth()->user()->id
            ];
        } else {
            $data += [
                "created_by" => auth()->user()->id
            ];
        }

        $dataFacultyLoadMonitoringJustification = FacultyLoadMonitoringJustification::updateOrCreate([
            "id" => $request->id,
        ], $data);

        if ($dataFacultyLoadMonitoringJustification) {
            $findFacultyLoadMonitoring = FacultyLoadMonitoring::find($request->faculty_load_monitoring_id);

            if ($findFacultyLoadMonitoring) {
                if ($findStatus->code == "S-08") {
                    $findStatusNew = RefStatus::firstWhere("code", "S-01");

                    $findFacultyLoadMonitoring->update([
                        "update_status_id" => $findStatusNew->id,
                        "update_remarks" => $request->remarks2,
                    ]);
                } else  if ($findStatus->code == "S-09") {
                    $findStatusNew = RefStatus::firstWhere("code", "S-09");

                    $findFacultyLoadMonitoring->update([
                        "update_status_id" => $findStatusNew->id,
                        "update_remarks" => $request->remarks2,
                    ]);
                }
            }

            $ret = [
                "success" => true,
                "message" => "Justification " . ($request->id ? "updated" : "saved") . " successfully"
            ];
        }

        return response()->json($ret, 200);
    }

    public function flm_view_proof_justification(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Justification not " . ($request->id ? "updated" : "saved"),
            "request" => $request->all()
        ];

        $data = [
            "faculty_load_monitoring_id" => $request->faculty_load_monitoring_id,
            "remarks" => $request->remarks,
        ];
    }

    public function flm_justification_approved(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data not approved",
            "data" => $request->all(),
            '$host = $request->getHttpHost();' => $request->getHttpHost()
        ];

        // if($request->has("ids") && count($request->ids) > 0) {
        //     foreach ($ids as $key => $value) {
        //         # code...
        //     }
        // }

        return response()->json($ret, 200);
    }
}
