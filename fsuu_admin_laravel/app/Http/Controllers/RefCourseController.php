<?php

namespace App\Http\Controllers;

use App\Models\RefCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RefCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $department = "SELECT department_name FROM ref_departments WHERE ref_departments.id = ref_courses.department_id";

        $data = RefCourse::select([
            '*',
            DB::raw("($department) as department"),
        ])->get();

        $data = RefCourse::where(function ($query) use ($request, $department) {
            if ($request->search) {
                $query->orWhere('course_code', 'LIKE', "%$request->search%");
                $query->orWhere('course_name', 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($department)"), 'LIKE', "%$request->search%");
            }
        });

        if ($request->isTrash == 1) {
            $data->onlyTrashed();
        }

        if ($request->department_id) {
            $data = $data->where(function ($query) use ($request) {
                $query->orWhere("department_id", $request->department_id);
                $query->orWhereNull("department_id");
            });
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
        $ret  = [
            "success" => false,
            "message" => "Data not " . ($request->id ? "update" : "saved")
        ];

        $request->validate([
            'department_id' => 'required',
            'course_name' => [
                'required',
                Rule::unique('ref_courses')->where(function ($query) use ($request) {
                    return $query->where('department_id', $request->department_id)
                        ->where('deleted_at', null);
                })
            ],
            // 'course_code' => 'required',
        ]);

        $data = [
            "department_id" => $request->department_id,
            "course_name" => $request->course_name,
            // "course_code" => $request->course_code,
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

        $examCategory = RefCourse::updateOrCreate([
            "id" => $request->id,
        ], $data);

        if ($examCategory) {
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
     * @param  \App\Models\RefCourse  $refCourse
     * @return \Illuminate\Http\Response
     */
    public function show(RefCourse $refCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefCourse  $refCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefCourse $refCourse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefCourse  $refCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findCourse = RefCourse::find($id);


        if ($findCourse) {
            if ($findCourse->delete()) {

                $ret = [
                    "success" => true,
                    "message" => "Data deleted successfully"
                ];
            }
        }
        return response()->json($ret, 200);
    }

    public function multiple_archived_course(Request $request)
    {
        $ret = [
            'success' => false,
            'message' => 'Data not archived!',
            'data' => $request->ids,
        ];

        if ($request->has('ids') && count($request->ids) > 0) {
            foreach ($request->ids as $key => $value) {
                $findCourse = RefCourse::find($value);

                if ($findCourse) {
                    if ($request->isTrash == 0) {
                        $findCourse->fill([
                            'deleted_by' => auth()->user()->id,
                            'deleted_at' => now(),
                        ])->save();
                    } else if ($request->isTrash == 1 && $findCourse) {
                        $findCourse->fill([
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
