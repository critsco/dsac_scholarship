<?php

namespace App\Http\Controllers;

use App\Models\RefStatusCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefStatusCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = RefStatusCategory::where(function ($query) use ($request) {
            if ($request->search) {
                $query->orWhere('status_category', 'LIKE', "%$request->search%");
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
            'status_category' => [
                'required',
                Rule::unique('status_categories')->ignore($request->id)
            ],
        ]);

        $data = [
            "status_category" => $request->status_category,
        ];

        if ($request->id) {
            $data += [
                "updated_by" => auth()->user()->id
            ];
        } else {
            $lastCode = "";

            $findStatusCategory = RefStatusCategory::orderBy("id", "desc")->first();

            if ($findStatusCategory) {
                $exp_code = $findStatusCategory->code;
                $lastCode = "SC-" . sprintf("%02d", explode("-", $exp_code)[1] + 1);
            } else {
                $lastCode = "SC-01";
            }

            $data += [
                "created_by" => auth()->user()->id,
                "code" => $lastCode
            ];
        }

        $statusCategory = RefStatusCategory::updateOrCreate([
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
     * @param  \App\Models\RefStatusCategory  $refStatusCategory
     * @return \Illuminate\Http\Response
     */
    public function show(RefStatusCategory $refStatusCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefStatusCategory  $refStatusCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefStatusCategory $refStatusCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefStatusCategory  $refStatusCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findData = RefStatusCategory::find($id);

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
