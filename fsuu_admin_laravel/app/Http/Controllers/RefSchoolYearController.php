<?php

namespace App\Http\Controllers;

use App\Models\RefSchoolYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefSchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = RefSchoolYear::where(function ($query) use ($request) {
            if ($request->search) {
                $query->orWhere('sy_from', 'LIKE', "%$request->search%");
                $query->orWhere('sy_to', 'LIKE', "%$request->search%");
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
            'sy_from' => [
                'required',
                Rule::unique('ref_school_years')->where(function ($query) use ($request) {
                    return $query->where('sy_to', $request->sy_to);
                })->ignore($request->id)
            ],

        ]);

        $data = [
            "sy_from" => $request->sy_from,
            "sy_to" => $request->sy_to,
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

        $findSchoolYear = RefSchoolYear::updateOrCreate([
            "id" => $request->id,
        ], $data);

        if ($findSchoolYear) {
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
     * @param  \App\Models\RefSchoolYear  $refSchoolYear
     * @return \Illuminate\Http\Response
     */
    public function show(RefSchoolYear $refSchoolYear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefSchoolYear  $refSchoolYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefSchoolYear $refSchoolYear)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefSchoolYear  $refSchoolYear
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findData = RefSchoolYear::find($id);

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
