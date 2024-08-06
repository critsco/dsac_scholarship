<?php

namespace App\Http\Controllers;

use App\Models\RefTimeSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RefTimeScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data = RefTimeSchedule::where(function ($query) use ($request) {
            if ($request->search) {
                $query->orWhere('ref_time_schedule', 'Like', "%$request->search%");
            }
        });

        if ($request->sort_field && $request->sort_order) {
            if (
                $request->sort_field != '' && $request->sort_field != 'undefined' && $request->sort_field != 'null' &&
                $request->sort_order != '' && $request->sort_order != 'undefined' && $request->sort_order != 'null'
            ) {
                $data = $data->orderBy(isset($request->sort_field) ? $request->sort_field : 'id', isset($request->sort_order) ? $request->sort_order : 'asc');
            }
        } else {
            $data = $data->orderBy('id', 'asc');
        }

        if ($request->page_size) {
            $data = $data->limit($request->page_size)
                ->paginate($request->page_size, ['*'], 'page', $request->page)
                ->toArray();
        } else {
            $data = $data->get();
        }

        return response()->json([
            'success' => true,
            'data' => $data
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
            "message" => "Data not " . ($request->id ? "update" : "saved")
        ];


        $request->validate([
            'time_in' => [
                'required',
                Rule::unique('ref_time_schedules')->where(function ($query) use ($request) {
                    return $query->where('time_out', $request->time_out)
                        ->where('time_in_meridiem', $request->time_in_meridiem)
                        ->where('time_out_meridiem', $request->time_out_meridiem);
                })->ignore($request->id),
            ],
        ]);


        $data = [
            "time_in" => $request->time_in,
            "time_in_meridiem" => $request->time_in_meridiem,
            "time_out" => $request->time_out,
            "time_out_meridiem" => $request->time_out_meridiem,
        ];

        if ($request->id) {
            $data += [
                "updated_by" => auth()->user()->id
            ];
        } else {
            $data += [
                "created_by" =>  auth()->user()->id
            ];
        }

        $time = RefTimeSchedule::updateOrCreate([
            "id" => $request->id,
        ], $data);

        if ($time) {
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
     * @param  \App\Models\RefTimeSchedule  $refTimeSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(RefTimeSchedule $refTimeSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefTimeSchedule  $refTimeSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefTimeSchedule $refTimeSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefTimeSchedule  $refTimeSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret  = [
            "success" => false,
            "message" => "Data not delete"
        ];

        $find = RefTimeSchedule::find($id);

        if ($find) {
            if ($find->delete()) {
                $ret  = [
                    "success" => true,
                    "message" => "Data deleted successfully"
                ];
            }
        }

        return response()->json($ret, 200);
    }
}
