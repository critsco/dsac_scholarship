<?php

namespace App\Http\Controllers;

use App\Exports\FacultyLoadReportExport;
use App\Models\FacultyLoadMonitoring;
use App\Models\ProfileDepartment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class FacultyLoadMonitoringController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		// $faculty_load = "SELECT id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id";
		$status = "SELECT `status` FROM ref_statuses WHERE ref_statuses.id = faculty_load_monitorings.status_id";
		$status_update = "SELECT `status` FROM ref_statuses WHERE ref_statuses.id = faculty_load_monitorings.update_status_id";
		$building = "SELECT building FROM ref_buildings WHERE ref_buildings.id = (SELECT ref_rooms.building_id FROM ref_rooms WHERE ref_rooms.id = (SELECT room_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id))";
		$floor = "SELECT floor FROM ref_floors WHERE ref_floors.id = (SELECT ref_rooms.floor_id FROM ref_rooms WHERE ref_rooms.id = (SELECT room_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id))";
		$room_code = "SELECT room_code FROM ref_rooms WHERE ref_rooms.id = (SELECT room_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id)";
		$building_id = "SELECT building_id FROM ref_rooms WHERE ref_rooms.id = (SELECT room_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id)";
		$subject_code = "SELECT code FROM ref_subjects WHERE ref_subjects.id = (SELECT subject_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id)";
		$school_year = "SELECT CONCAT(`sy_from`, '-', `sy_to`) FROM ref_school_years WHERE ref_school_years.id = (SELECT school_year_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id)";
		$semester = "SELECT semester FROM ref_semesters WHERE ref_semesters.id = (SELECT semester_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id)";
		$section = "SELECT section FROM ref_sections WHERE ref_sections.id = (SELECT section_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id)";
		$faculty_no = "SELECT (SELECT school_id FROM profiles WHERE profiles.id = faculty_loads.profile_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id";
		$fullname = "SELECT (SELECT CONCAT(firstname, IF(lastname, CONCAT(' ', lastname), '')) FROM profiles WHERE profiles.id = faculty_loads.profile_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id";
		$time = "SELECT CONCAT(time_in,'-', time_out, ' ', meridian) FROM faculty_load_schedules WHERE faculty_load_schedules.id = faculty_load_schedule_id";
		$created_at_format = "DATE_FORMAT(faculty_load_monitorings.created_at, '%m/%d/%Y')";
		$time_total_absent = "SELECT 
                                DATE_FORMAT(
                                    TIMEDIFF(
                                        STR_TO_DATE(
                                            CONCAT( IF(meridian = 'AM', HOUR(time_out), IF(HOUR(time_out) = 12 , HOUR(time_out), HOUR(time_out) + 12) ),':', MINUTE(time_out) ),
                                            '%H:%i' 
                                        ),
                                        STR_TO_DATE(
                                            CONCAT(
                                                IF(
                                                    (CASE 
                                                        WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >=7 AND HOUR(time_out) < 12 AND meridian = 'AM' THEN 'AM'
                                                        WHEN HOUR(time_in) = 12 AND HOUR(time_out) = 12 AND meridian = 'PM' THEN 'PM'
                                                        WHEN HOUR(time_in) >= 1 AND HOUR(time_in) <= 10 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) > HOUR(time_out) AND meridian = 'PM' THEN 'AM'
                                                        WHEN HOUR(time_in) >= 1 AND HOUR(time_in) <= 10 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) < HOUR(time_out) AND meridian = 'PM' THEN 'PM'
                                                        WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) > HOUR(time_out) AND meridian = 'PM' THEN 'AM'
                                                        WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) < HOUR(time_out) AND meridian = 'PM' THEN 'PM'
                                                        WHEN HOUR(time_in) = 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND meridian = 'PM' THEN 'PM'
                                                        WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) = 12 AND meridian = 'PM' THEN 'AM'
                                                        ELSE meridian
                                                    END) = 'AM', HOUR(time_in), IF(HOUR(time_in) = 12, HOUR(time_in), HOUR(time_in) + 12)
                                                ),
                                                ':', MINUTE(time_in)
                                            ),
                                            '%H:%i' 
                                        )
                                    ),
                                    '%H:%i'
                                )
                            FROM faculty_load_schedules WHERE faculty_load_schedules.id = faculty_load_schedule_id";

		$time_total_absent_decimal = "SELECT 
                            ROUND(
                            (HOUR(TIMEDIFF(
                            STR_TO_DATE(
                                CONCAT( IF(meridian = 'AM', HOUR(time_out), IF(HOUR(time_out) = 12 , HOUR(time_out), HOUR(time_out) + 12) ),':', MINUTE(time_out) ),
                                '%H:%i' 
                            ),
                            STR_TO_DATE(
                                CONCAT(
                                    IF(
                                        (CASE 
                                            WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >=7 AND HOUR(time_out) < 12 AND meridian = 'AM' THEN 'AM'
                                            WHEN HOUR(time_in) = 12 AND HOUR(time_out) = 12 AND meridian = 'PM' THEN 'PM'
                                            WHEN HOUR(time_in) >= 1 AND HOUR(time_in) <= 10 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) > HOUR(time_out) AND meridian = 'PM' THEN 'AM'
                                            WHEN HOUR(time_in) >= 1 AND HOUR(time_in) <= 10 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) < HOUR(time_out) AND meridian = 'PM' THEN 'PM'
                                            WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) > HOUR(time_out) AND meridian = 'PM' THEN 'AM'
                                            WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) < HOUR(time_out) AND meridian = 'PM' THEN 'PM'
                                            WHEN HOUR(time_in) = 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND meridian = 'PM' THEN 'PM'
                                            WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) = 12 AND meridian = 'PM' THEN 'AM'
                                            ELSE meridian
                                        END) = 'AM', HOUR(time_in), IF(HOUR(time_in) = 12, HOUR(time_in), HOUR(time_in) + 12)
                                    ),
                                    ':', MINUTE(time_in)
                                ),
                                '%H:%i' 
                            )
                        )) + 
                        ( MINUTE(TIMEDIFF(
                            STR_TO_DATE(
                                CONCAT( IF(meridian = 'AM', HOUR(time_out), IF(HOUR(time_out) = 12 , HOUR(time_out), HOUR(time_out) + 12) ),':', MINUTE(time_out) ),
                                '%H:%i' 
                            ),
                            STR_TO_DATE(
                                CONCAT(
                                    IF(
                                        (CASE 
                                            WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >=7 AND HOUR(time_out) < 12 AND meridian = 'AM' THEN 'AM'
                                            WHEN HOUR(time_in) = 12 AND HOUR(time_out) = 12 AND meridian = 'PM' THEN 'PM'
                                            WHEN HOUR(time_in) >= 1 AND HOUR(time_in) <= 10 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) > HOUR(time_out) AND meridian = 'PM' THEN 'AM'
                                            WHEN HOUR(time_in) >= 1 AND HOUR(time_in) <= 10 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) < HOUR(time_out) AND meridian = 'PM' THEN 'PM'
                                            WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) > HOUR(time_out) AND meridian = 'PM' THEN 'AM'
                                            WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) < HOUR(time_out) AND meridian = 'PM' THEN 'PM'
                                            WHEN HOUR(time_in) = 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND meridian = 'PM' THEN 'PM'
                                            WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) = 12 AND meridian = 'PM' THEN 'AM'
                                            ELSE meridian
                                        END) = 'AM', HOUR(time_in), IF(HOUR(time_in) = 12, HOUR(time_in), HOUR(time_in) + 12)
                                    ),
                                    ':', MINUTE(time_in)
                                ),
                                '%H:%i' 
                            )
                        )) * (1/60) ) ), 2
                        )
                    FROM faculty_load_schedules WHERE faculty_load_schedules.id = faculty_load_schedule_id";

		$total_deduction = "IF( rate != '', ( SELECT 
                                                ROUND(
                                                (HOUR(TIMEDIFF(
                                                STR_TO_DATE(
                                                    CONCAT( IF(meridian = 'AM', HOUR(time_out), IF(HOUR(time_out) = 12 , HOUR(time_out), HOUR(time_out) + 12) ),':', MINUTE(time_out) ),
                                                    '%H:%i' 
                                                ),
                                                STR_TO_DATE(
                                                    CONCAT(
                                                        IF(
                                                            (CASE 
                                                                WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >=7 AND HOUR(time_out) < 12 AND meridian = 'AM' THEN 'AM'
                                                                WHEN HOUR(time_in) = 12 AND HOUR(time_out) = 12 AND meridian = 'PM' THEN 'PM'
                                                                WHEN HOUR(time_in) >= 1 AND HOUR(time_in) <= 10 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) > HOUR(time_out) AND meridian = 'PM' THEN 'AM'
                                                                WHEN HOUR(time_in) >= 1 AND HOUR(time_in) <= 10 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) < HOUR(time_out) AND meridian = 'PM' THEN 'PM'
                                                                WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) > HOUR(time_out) AND meridian = 'PM' THEN 'AM'
                                                                WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) < HOUR(time_out) AND meridian = 'PM' THEN 'PM'
                                                                WHEN HOUR(time_in) = 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND meridian = 'PM' THEN 'PM'
                                                                WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) = 12 AND meridian = 'PM' THEN 'AM'
                                                                ELSE meridian
                                                            END) = 'AM', HOUR(time_in), IF(HOUR(time_in) = 12, HOUR(time_in), HOUR(time_in) + 12)
                                                        ),
                                                        ':', MINUTE(time_in)
                                                    ),
                                                    '%H:%i' 
                                                )
                                            )) + 
                                            ( MINUTE(TIMEDIFF(
                                                STR_TO_DATE(
                                                    CONCAT( IF(meridian = 'AM', HOUR(time_out), IF(HOUR(time_out) = 12 , HOUR(time_out), HOUR(time_out) + 12) ),':', MINUTE(time_out) ),
                                                    '%H:%i' 
                                                ),
                                                STR_TO_DATE(
                                                    CONCAT(
                                                        IF(
                                                            (CASE 
                                                                WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >=7 AND HOUR(time_out) < 12 AND meridian = 'AM' THEN 'AM'
                                                                WHEN HOUR(time_in) = 12 AND HOUR(time_out) = 12 AND meridian = 'PM' THEN 'PM'
                                                                WHEN HOUR(time_in) >= 1 AND HOUR(time_in) <= 10 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) > HOUR(time_out) AND meridian = 'PM' THEN 'AM'
                                                                WHEN HOUR(time_in) >= 1 AND HOUR(time_in) <= 10 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) < HOUR(time_out) AND meridian = 'PM' THEN 'PM'
                                                                WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) > HOUR(time_out) AND meridian = 'PM' THEN 'AM'
                                                                WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND HOUR(time_in) < HOUR(time_out) AND meridian = 'PM' THEN 'PM'
                                                                WHEN HOUR(time_in) = 12 AND HOUR(time_out) >= 1 AND HOUR(time_out) <= 10 AND meridian = 'PM' THEN 'PM'
                                                                WHEN HOUR(time_in) >= 7 AND HOUR(time_in) < 12 AND HOUR(time_out) = 12 AND meridian = 'PM' THEN 'AM'
                                                                ELSE meridian
                                                            END) = 'AM', HOUR(time_in), IF(HOUR(time_in) = 12, HOUR(time_in), HOUR(time_in) + 12)
                                                        ),
                                                        ':', MINUTE(time_in)
                                                    ),
                                                    '%H:%i' 
                                                )
                                            )) * (1/60) ) ), 2
                                            )
                                        FROM faculty_load_schedules WHERE faculty_load_schedules.id = faculty_load_schedule_id ) * rate, '' ) ";

		$data = FacultyLoadMonitoring::select([
			"*",
			DB::raw("($status) status"),
			DB::raw("($status_update) status_update"),
			DB::raw("($building) building"),
			DB::raw("($floor) floor"),
			DB::raw("($room_code) room_code"),
			DB::raw("($building_id) building_id"),
			DB::raw("($subject_code) code"),
			DB::raw("($school_year) school_year"),
			DB::raw("($semester) semester"),
			DB::raw("($section) section"),
			DB::raw("($faculty_no) faculty_no"),
			DB::raw("($fullname) fullname"),
			DB::raw("($time) time"),
			DB::raw("($created_at_format) created_at_format"),
			DB::raw("($time_total_absent) time_total_absent"),
			DB::raw("($time_total_absent_decimal) time_total_absent_decimal"),
			DB::raw("($total_deduction) total_deduction"),
		])
			->with([
				"faculty_load_monitoring_justification" => function ($query) {
					$query->select([
						"*",
						DB::raw("(SELECT code FROM ref_statuses WHERE ref_statuses.id = status_id) code")
					]);
				},
				"attachments"
			]);

		$data->where(function ($query) use ($request, $building, $floor, $room_code, $subject_code, $school_year, $semester, $section, $fullname, $faculty_no, $status, $status_update, $time, $time_total_absent, $time_total_absent_decimal, $total_deduction, $created_at_format) {
			if ($request->search) {
				$query->orWhere(DB::raw("($building)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($floor)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($room_code)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($subject_code)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($school_year)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($semester)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($section)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($faculty_no)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($fullname)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($status)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($status_update)"), 'LIKE', "%$request->search%");
				// $query->orWhere(DB::raw("($time)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($time_total_absent)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($time_total_absent_decimal)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($total_deduction)"), 'LIKE', "%$request->search%");
				$query->orWhere(DB::raw("($created_at_format)"), 'LIKE', "%$request->search%");
				$query->orWhere("remarks", 'LIKE', "%$request->search%");
				$query->orWhere("update_remarks", 'LIKE', "%$request->search%");
				// $query->orWhere('time_out', 'LIKE', "%$request->search%");
				// $query->orWhere('day', 'LIKE', "%$request->search%");
				// $query->orWhere('meridian', 'LIKE', "%$request->search%");
			}
		});

		//if ($request->status_id) {
		//  $data->where("status_id", $request->status_id);
		//}

		//if ($request->department_id) {
		//	$data->where(DB::raw("(SELECT department_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_id)"), $request->department_id);
		//}

		// for role Faculty Admin 
		if (auth()->user()->user_role_id == 3) {
			$profileDepartment = ProfileDepartment::where(DB::raw("(SELECT user_id FROM profiles WHERE profiles.id = profile_id)"), auth()->user()->id)
				->pluck("department_id");

			$data->whereIn(DB::raw("(SELECT department_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_id)"), $profileDepartment);
		}
		// end for role Faculty Admin 

		if ($request->from) {
			if ($request->from == 'page_monitoring') {
				$data->where(DB::raw("( SELECT count(*) FROM faculty_load_monitorings WHERE faculty_load_id = faculty_loads.id AND DATE ( faculty_load_monitorings.created_at ) = DATE ( NOW()) )"), "=", 0);
			} else if ($request->from == '/faculty-load/deduction') {
				$data->where(function ($query) {
					$query->orWhere(DB::raw("( SELECT ( SELECT `code` FROM ref_statuses WHERE ref_statuses.id = faculty_load_monitoring_justifications.status_id ) FROM faculty_load_monitoring_justifications WHERE faculty_load_monitoring_id = faculty_load_monitorings.id )"), "!=", "S-08");
					$query->orWhere(DB::raw("( SELECT count(*) FROM faculty_load_monitoring_justifications WHERE faculty_load_monitoring_id = faculty_load_monitorings.id )"), "=", 0);
				});
				if ($request->status_id) {
					$data->where("status_id", $request->status_id);
				} else {
					$data->where("status_id", "!=", 1);
				}
			} else if ($request->from == "/faculty-load/present-list") {
				if ($request->status_id) {
					$data->where("status_id", $request->status_id);
				} else {
					$data = $data->where(function ($query) {
						$query->where("status_id", 1)
							->orWhereHas("faculty_load_monitoring_justification", function ($query) {
								$query->where("status_id", 8);
							});
					});
				}
			} else if ($request->from == "/faculty-load/absent-list") {
				if ($request->status_id) {
					$data->where("status_id", $request->status_id);
				} else {
					$data->where("status_id", "!=", 1);
				}
			}
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

		if ($request->page) {
			$data = $data->limit($request->page_size)
				->paginate($request->page_size, ['*'], 'page', $request->page)
				->toArray();
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
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\FacultyLoadMonitoring  $facultyLoadMonitoring
	 * @return \Illuminate\Http\Response
	 */
	public function show(FacultyLoadMonitoring $facultyLoadMonitoring)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\FacultyLoadMonitoring  $facultyLoadMonitoring
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, FacultyLoadMonitoring $facultyLoadMonitoring)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\FacultyLoadMonitoring  $facultyLoadMonitoring
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(FacultyLoadMonitoring $facultyLoadMonitoring)
	{
		//
	}


	public function faculty_load_monitoring_remarks(Request $request)
	{
		$ret = [
			"success" => false,
			"message" => "Status not saved"
		];

		$check = FacultyLoadMonitoring::find($request->id);

		if ($check) {
			$data = [
				"remarks" => $request->remarks,
			];

			if ($request->id) {
				$data += [
					"updated_by" => auth()->user()->id
				];
			} else {
				$data += [
					"created_by" => auth()->user()->id
				];
			}

			$dataCreated = $check->fill($data);

			if ($dataCreated->save()) {
				$ret = [
					"success" => true,
					"message" => "Remarks updated successfully"
				];
			}
		}

		return response()->json($ret, 200);
	}

	public function faculty_load_deduction(Request $request)
	{
		$ret = [
			"success" => false,
			"message" => "Data not updated"
		];

		$find = FacultyLoadMonitoring::find($request->id);

		if ($find) {
			$data = [
				"rate" => $request->rate,
				"rate_id" => $request->rate_id,
				"updated_by" => auth()->user()->id
			];

			$update = $find->fill($data);

			if ($update->save()) {
				$ret = [
					"success" => true,
					"message" => "Data updated successfully"
				];
			}
		}

		return response()->json($ret, 200);
	}


	public function faculty_load_monitoring_graph(Request $request)
	{
		$ret = [
			"success" => false,
			"message" => "No data"
		];

		if ($request->action == 'timely') {
			$ret = [
				"success" => true,
				"data" => $this->facultyLoadMonitoringGraphDataByTime($request)
			];
		} else if ($request->action == 'week_days') {
			$ret = [
				"success" => true,
				"data" => $this->facultyLoadMonitoringGraphDataByWeekDay($request)
			];
		} else if ($request->action == 'monthly') {
			$ret = [
				"success" => true,
				"data" => $this->facultyLoadMonitoringDataByMonthly($request)
			];
		} else if ($request->action == 'daily') {
			$ret = [
				"success" => true,
				"data" => $this->facultyLoadMonitoringGraphDataByDaily($request)
			];
		}

		return response()->json($ret, 200);
	}

	public function facultyLoadMonitoringGraphDataByTime($request)
	{
		$data_series_name   = [];
		$data_series_value  = [];

		$department_id = $request->department_id;

		$year = date("Y");

		$data_series_name = \App\Models\FacultyLoadSchedule::select([
			DB::raw("CONCAT(time_in, '-', time_out, ' ', meridian) time_in_out")
		]);

		// if ($request->user_role_id == 3) {
		//     $data_series_name = $data_series_name->where(DB::raw("(SELECT COUNT(*) FROM `profile_departments` LEFT JOIN `profiles` ON profile_departments.profile_id = `profiles`.id WHERE `profiles`.id = faculty_loads.profile_id AND department_id=$request->department_id)"), ">", 0);
		// }

		$data_series_name = $data_series_name->orderBy("meridian", "asc")
			->groupBy("time_in_out")
			->pluck("time_in_out");

		$dataStatus = new \App\Models\RefStatus();

		if ($request->status_id) {
			$dataStatus = $dataStatus->where("id", $request->status_id);
		}

		$school_year_id = "";
		if ($request->school_year_id) {
			$school_year_id = $request->school_year_id;
		} else {
			$school_year = \App\Models\RefSchoolYear::where("status", 1)->first();
			if ($school_year) {
				$school_year_id = $school_year->id;
			}
		}

		$dataStatus = $dataStatus->get();
		foreach ($dataStatus as $keyStat => $valueStat) {
			$data_sub_series_value = [];

			foreach ($data_series_name as $keyS => $valueS) {
				$data_result = FacultyLoadMonitoring::where(DB::raw("(SELECT CONCAT(time_in, '-', time_out, ' ', meridian) FROM faculty_load_schedules WHERE faculty_load_schedules.id = faculty_load_schedule_id)"), $valueS)
					// ->where(DB::raw("(SELECT school_year_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_id)"), $school_year_id)
					->where(DB::raw("YEAR(created_at)"), $year)
					->where("status_id", $valueStat->id);

				if ($department_id) {
					if ($department_id != 'all') {
						$data_result = $data_result->where(DB::raw("( SELECT department_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id )"), $department_id);
					}
				}

				$data_sub_series_value[] = $data_result->count();
			}

			$data_series_value[] = [
				"name"  => "$valueStat->status",
				"data"  => $data_sub_series_value,
			];
		}

		$data = [
			"data_series_name"  => $data_series_name,
			"data_series_value" => $data_series_value,
			"action"            => "timely",
			"downTo"            => "week_days",
		];

		return $data;
	}

	public function facultyLoadMonitoringGraphDataByWeekDay($request)
	{
		$data_series_name = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
		$data_series_value = [];

		$department_id = $request->department_id;
		$time = $request->time;
		$year = date("Y");

		$school_year_id = "";
		if ($request->school_year_id) {
			$school_year_id = $request->school_year_id;
		} else {
			$school_year = \App\Models\RefSchoolYear::where("status", 1)->first();
			if ($school_year) {
				$school_year_id = $school_year->id;
			}
		}

		$dataStatus = new \App\Models\RefStatus();

		if ($request->status_id) {
			$dataStatus = $dataStatus->where("id", $request->status_id);
		}

		$dataStatus = $dataStatus->get();

		foreach ($dataStatus as $keyStat => $valueStat) {
			$data_sub_series_value = [];

			foreach ($data_series_name as $keyS => $valueS) {
				$data_result = FacultyLoadMonitoring::where(DB::raw("(SELECT CONCAT(time_in, '-', time_out, ' ', meridian) FROM faculty_load_schedules WHERE faculty_load_schedules.id = faculty_load_schedule_id)"), $time)
					// ->where(DB::raw("(SELECT school_year_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_id)"), $school_year_id)
					->where(DB::raw("( DATE_FORMAT(created_at, '%a') )"), $valueS)
					->where(DB::raw("YEAR(created_at)"), $year)
					->where("status_id", $valueStat->id);

				if ($department_id) {
					if ($department_id != 'all') {
						$data_result = $data_result->where(DB::raw("( SELECT department_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id )"), $department_id);
					}
				}

				$data_sub_series_value[] = $data_result->count();
			}

			$data_series_value[] = [
				"name"  => "$valueStat->status",
				"data"  => $data_sub_series_value,
			];
		}

		$data = [
			"data_series_name"  => $data_series_name,
			"data_series_value" => $data_series_value,
			"action"            => "week_days",
			"downTo"            => "monthly",
		];

		return $data;
	}

	public function facultyLoadMonitoringDataByMonthly($request)
	{
		$data_series_name = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Nov", "Dec"];
		$data_series_value = [];

		$department_id = $request->department_id;
		$time = $request->time;
		$reqday = $request->day;
		$year = date("Y");

		$school_year_id = "";
		if ($request->school_year_id) {
			$school_year_id = $request->school_year_id;
		} else {
			$school_year = \App\Models\RefSchoolYear::where("status", 1)->first();
			if ($school_year) {
				$school_year_id = $school_year->id;
			}
		}

		$dataStatus = new \App\Models\RefStatus();

		if ($request->status_id) {
			$dataStatus = $dataStatus->where("id", $request->status_id);
		}

		$dataStatus = $dataStatus->get();

		foreach ($dataStatus as $keyStat => $valueStat) {
			$data_sub_series_value = [];

			foreach ($data_series_name as $keyS => $valueS) {
				$data_result = FacultyLoadMonitoring::where(DB::raw("(SELECT CONCAT(time_in, '-', time_out, ' ', meridian) FROM faculty_load_schedules WHERE faculty_load_schedules.id = faculty_load_schedule_id)"), $time)
					->where(DB::raw("DATE_FORMAT(created_at, '%a')"), $reqday)
					// ->where(DB::raw("(SELECT school_year_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_id)"), $school_year_id)
					->where(DB::raw("DATE_FORMAT(created_at, '%b')"), $valueS)
					->where(DB::raw("YEAR(created_at)"), $year)
					->where("status_id", $valueStat->id);

				if ($department_id) {
					if ($department_id != 'all') {
						$data_result = $data_result->where(DB::raw("( SELECT department_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id )"), $department_id);
					}
				}

				$data_sub_series_value[] = $data_result->count();
			}

			$data_series_value[] = [
				"name"  => "$valueStat->status",
				"data"  => $data_sub_series_value,
			];
		}

		$data = [
			"data_series_name"  => $data_series_name,
			"data_series_value" => $data_series_value,
			"action"            => "monthly",
			"downTo"            => "daily",
		];

		return $data;
	}

	public function facultyLoadMonitoringGraphDataByDaily($request)
	{
		$data_series_value = [];

		$department_id = $request->department_id;
		$time = $request->time;
		$reqday = $request->day;
		$year = date("Y");

		$school_year_id = "";
		if ($request->school_year_id) {
			$school_year_id = $request->school_year_id;
		} else {
			$school_year = \App\Models\RefSchoolYear::where("status", 1)->first();
			if ($school_year) {
				$school_year_id = $school_year->id;
			}
		}

		$dataStatus = new \App\Models\RefStatus();

		if ($request->status_id) {
			$dataStatus = $dataStatus->where("id", $request->status_id);
		}

		$dataStatus = $dataStatus->get();

		$year = date("Y");

		$month = date("m", strtotime("$year-$request->month"));

		$data_series_name = $this->getAllMondayByYearMonth($year, $month, $reqday);

		foreach ($dataStatus as $keyStat => $valueStat) {
			$data_sub_series_value = [];

			foreach ($data_series_name as $keyS => $valueS) {
				$data_result = FacultyLoadMonitoring::where(DB::raw("(SELECT CONCAT(time_in, '-', time_out, ' ', meridian) FROM faculty_load_schedules WHERE faculty_load_schedules.id = faculty_load_schedule_id)"), $time)
					->where(DB::raw("DATE_FORMAT(created_at, '%a')"), $reqday)
					// ->where(DB::raw("(SELECT school_year_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_id)"), $school_year_id)
					->where(DB::raw("DATE(created_at)"), $valueS)
					->where(DB::raw("YEAR(created_at)"), $year)
					->where("status_id", $valueStat->id);

				if ($department_id) {
					if ($department_id != 'all') {
						$data_result = $data_result->where(DB::raw("( SELECT department_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id )"), $department_id);
					}
				}

				$data_sub_series_value[] = $data_result->count();
			}

			$data_series_value[] = [
				"name"  => "$valueStat->status",
				"data"  => $data_sub_series_value,
			];
		}

		$data = [
			"data_series_name"  => $data_series_name,
			"data_series_value" => $data_series_value,
			"action"            => "daily",
			"downTo"            => "timely",
		];

		return $data;
	}

	public function getAllMondayByYearMonth($year, $month, $reqday)
	{
		$commonDays = [];

		$startOfMonth = Carbon::create($year, $month, 1);
		$endOfMonth = $startOfMonth->copy()->endOfMonth();

		for ($day = $startOfMonth; $day->lte($endOfMonth); $day->addDay()) {
			$ifWeekDay = Carbon::MONDAY;

			if ($reqday == "Mon") {
				$ifWeekDay = Carbon::MONDAY;
			} else if ($reqday == "Tue") {
				$ifWeekDay = Carbon::TUESDAY;
			} else if ($reqday == "Wed") {
				$ifWeekDay = Carbon::WEDNESDAY;
			} else if ($reqday == "Thu") {
				$ifWeekDay = Carbon::THURSDAY;
			} else if ($reqday == "Fri") {
				$ifWeekDay = Carbon::FRIDAY;
			} else if ($reqday == "Sat") {
				$ifWeekDay = Carbon::SATURDAY;
			} else if ($reqday == "Sun") {
				$ifWeekDay = Carbon::SUNDAY;
			}

			if (
				$day->isDayOfWeek($ifWeekDay)
			) {
				$commonDays[] = $day->format('Y-m-d');
			}
		}

		return $commonDays;
	}

	public function faculty_load_monitoring_graph2(Request $request)
	{
		$ret = [
			"success" => true,
			"data" => $this->facultyLoadMonitoringDataByFaculty($request)
		];

		return response()->json($ret, 200);
	}

	public function facultyLoadMonitoringDataByFaculty($request)
	{
		$data_series_name   = [];
		$data_series_value  = [];

		$dataStatus = new \App\Models\RefStatus();

		if ($request->status_id) {
			$dataStatus = $dataStatus->where("id", $request->status_id);
		}

		$dataStatus = $dataStatus->get();

		$data_series_name = \App\Models\Profile::whereNotIn("id", ["1", "2"])->pluck("firstname");

		$department_id = $request->department_id;

		$year = date("Y");

		foreach ($dataStatus as $key1 => $value1) {
			$data_sub_series_value = [];

			foreach ($data_series_name as $key2 => $value2) {
				$data_result = FacultyLoadMonitoring::where(DB::raw("YEAR(created_at)"), $year)
					->where("status_id", $value1->id)
					->where(DB::raw("(SELECT (SELECT firstname FROM `profiles` WHERE `profiles`.id = faculty_loads.profile_id) FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id )"), $value2);

				if ($department_id) {
					if ($department_id != 'all') {
						$data_result = $data_result->where(DB::raw("( SELECT department_id FROM faculty_loads WHERE faculty_loads.id = faculty_load_monitorings.faculty_load_id )"), $department_id);
					}
				}

				$data_sub_series_value[] = $data_result->count();
			}

			$data_series_value[] = [
				"name"  => "$value1->status",
				"data"  => $data_sub_series_value,
			];
		}

		$data = [
			"data_series_name"  => $data_series_name,
			"data_series_value" => $data_series_value,
			"action"            => "",
			"downTo"            => "",
			"data_series_name" => $data_series_name
		];

		return $data;
	}

	public function faculty_load_report_print(Request $request)
	{
		$date_start = $request->date_start;
		$date_end = $request->date_end;
		$from = $request->from;

		return Excel::download(new FacultyLoadReportExport($date_start, $date_end, $from), 'faculty_monitoring_report.xls');
	}
}
