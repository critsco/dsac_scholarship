<?php

namespace App\Http\Controllers;

use App\Models\RefScholarship;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefScholarshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = RefScholarship::where(function ($query) use ($request) {
            if ($request->search) {
                $query->orWhere('name', 'LIKE', "%$request->search%");
                $query->orWhere('category', 'LIKE', "%$request->search%");
                $query->orWhere('grade_level', 'LIKE', "%$request->search%");
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
            'name' => [
                'required',
                Rule::unique('ref_scholarships')->where(function ($query) use ($request) {
                    return $query->where('category', $request->category)
                        ->where('description', $request->description)
                        ->where('start_date', $request->start_date)
                        ->where('end_date', $request->end_date);
                })->ignore($request->id),
            ],
        ]);

        $data = [
            "name" => $request->name,
            "description" => $request->description,
            "provider" => $request->provider,
            "category" => $request->category,
            "school_level_id" => $request->school_level_id,
            "benefits" => $request->benefits,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
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

        $findSchoolYear = RefScholarship::updateOrCreate([
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
     * @param  \App\Models\RefScholarship  $refScholarship
     * @return \Illuminate\Http\Response
     */
    public function show(RefScholarship $refScholarship)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefScholarship  $refScholarship
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefScholarship $refScholarship)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefScholarship  $refScholarship
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findData = RefScholarship::find($id);

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