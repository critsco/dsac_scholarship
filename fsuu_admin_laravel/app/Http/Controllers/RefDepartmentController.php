<?php

namespace App\Http\Controllers;

use App\Models\RefDepartment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = RefDepartment::where(function ($query) use ($request) {
            if ($request->search) {
                $query->orWhere('department_name', 'LIKE', "%$request->search%");
            }
        });

        if ($request->isTrash == 1) {
            $data->onlyTrashed();
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
            'department_name' => [
                'required',
                Rule::unique('ref_departments')->ignore($request->id)
            ],
        ]);

        $data = [
            "department_name" => $request->department_name,
        ];

        $department = RefDepartment::updateOrCreate([
            "id" => $request->id,
        ], $data);

        if ($department) {
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
     * @param  \App\Models\RefDepartment  $refDepartment
     * @return \Illuminate\Http\Response
     */
    public function show(RefDepartment $refDepartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefDepartment  $refDepartment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefDepartment $refDepartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefDepartment  $refDepartment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findData = RefDepartment::find($id);

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

    public function multiple_archived_department(Request $request)
    {
        $ret = [
            'success' => false,
            'message' => 'Data not archived!',
            'data' => $request->ids,
        ];

        if ($request->has('ids') && count($request->ids) > 0) {
            foreach ($request->ids as $key => $value) {
                $findDepartment = RefDepartment::find($value);

                if ($findDepartment) {
                    if ($request->isTrash == 0) {
                        $findDepartment->fill([
                            'deleted_by' => auth()->user()->id,
                            'deleted_at' => now(),
                        ])->save();
                    } else if ($request->isTrash == 1 && $findDepartment) {
                        $findDepartment->fill([
                            'updated_by' => auth()->user()->id,
                            'deleted_by' => NULL,
                            'deleted_at' => NULL,
                        ])->save();
                    }
                }
            }

            $ret = [
                'success' => true,
                'message' => 'Data ' . ($request->isTrash == 1 ? 'archived' : 'activated') . ' successfully!',
            ];
        }

        return response()->json($ret, 200);
    }
}
