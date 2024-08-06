<?php

namespace App\Http\Controllers;

use App\Models\RefStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RefStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status_category = "SELECT status_category FROM ref_status_categories WHERE ref_status_categories.id = status_category_id";
        $status_category_code = "SELECT code FROM ref_status_categories WHERE ref_status_categories.id = status_category_id";

        $data = RefStatus::select([
            "*",
            DB::raw("($status_category) status_category")
        ]);

        $data = $data->where(function ($query) use ($request, $status_category) {
            if ($request->search) {
                $query->orWhere(DB::raw("($status_category)"), 'LIKE', "%$request->search%");
                $query->orWhere('status', 'LIKE', "%$request->search%");
            }
        });

        if ($request->status_category_id) {
            $data = $data->where("status_category_id", $request->status_category_id);
        }
        if ($request->status_category_code) {
            $data = $data->where(DB::raw("($status_category_code)"), $request->status_category_code);
        }

        $requestFrom = [
            'PageFacultyLoadMonitoring',
            'PageFacultyMonitoringDashboard',
        ];
        if (in_array($request->from, $requestFrom)) {
            $data = $data->where(DB::raw("($status_category_code)"), $request->status_category_code)
                ->whereNotIn("code", ["S-10"]);
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
            'status_category_id' => [
                'required',
            ],
            'status' => [
                'required',
                Rule::unique('ref_statuses')->where(function ($query) use ($request) {
                    return $query->where('status_category_id', $request->status_category_id);
                })->ignore($request->id)
            ],
        ]);

        $data = [
            "status_category_id" => $request->status_category_id,
            "status" => $request->status,
        ];

        if ($request->id) {
            $data += [
                "updated_by" => auth()->user()->id
            ];
        } else {
            $lastCode = "";

            $findStatus = RefStatus::orderBy("id", "desc")->first();

            if ($findStatus) {
                $exp_code = $findStatus->code;
                $lastCode = "S-" . sprintf("%02d", explode("-", $exp_code)[1] + 1);
            } else {
                $lastCode = "S-01";
            }

            $data += [
                "created_by" => auth()->user()->id,
                "code" => $lastCode
            ];
        }

        $statusCategory = RefStatus::updateOrCreate([
            "id" => $request->id,
        ], $data);

        if ($statusCategory) {
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
     * @param  \App\Models\RefStatus  $refStatus
     * @return \Illuminate\Http\Response
     */
    public function show(RefStatus $refStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefStatus  $refStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefStatus $refStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefStatus  $refStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findData = RefStatus::find($id);

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
