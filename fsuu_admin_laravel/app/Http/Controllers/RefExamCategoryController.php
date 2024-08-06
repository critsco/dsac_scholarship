<?php

namespace App\Http\Controllers;

use App\Models\RefExamCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefExamCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = RefExamCategory::select([
            "*"
        ]);

        if ($request->isTrash == 1) {
            $data->onlyTrashed();
        }

        $data = RefExamCategory::where(function ($query) use ($request) {
            if ($request->search) {
                $query->orWhere('category', 'LIKE', "%$request->search%");
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
            'category' => [
                'required',
                Rule::unique('ref_exam_categories')->where(function ($query) use ($request) {
                    return $query->where('exam_fee', $request->exam_fee)
                        ->where('deleted_at', null);
                })
            ],
            'exam_fee' => 'required',
        ]);

        $data = [
            "category" => $request->category,
            "exam_fee" => $request->exam_fee,
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

        $examCategory = RefExamCategory::updateOrCreate([
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
     * @param  \App\Models\RefExamCategory  $refExamCategory
     * @return \Illuminate\Http\Response
     */
    public function show(RefExamCategory $refExamCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefExamCategory  $refExamCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefExamCategory $refExamCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefExamCategory  $refExamCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret  = [
            "success" => false,
            "message" => "Data not delete"
        ];

        $find = RefExamCategory::find($id);

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


    public function multiple_archived_exam_category(Request $request)
    {
        $ret = [
            'success' => false,
            'message' => 'Data not archived!',
            'data' => $request->ids,
        ];

        // $findUser = User::whereIn('id', $request->ids)->get();

        if ($request->has('ids') && count($request->ids) > 0) {
            foreach ($request->ids as $key => $value) {
                $findExamCategory = RefExamCategory::find($value);

                if ($findExamCategory) {
                    if ($request->isTrash == 0) {
                        $findExamCategory->fill([
                            'deleted_by' => auth()->user()->id,
                            'deleted_at' => now(),
                        ])->save();
                    } else if ($request->isTrash == 1 && $findExamCategory) {
                        $findExamCategory->fill([
                            'updated_by' => auth()->user()->id,
                            'deleted_by' => NULL,
                            'deleted_at' => NULL,
                        ])->save();

                        return response()->json(['message' => 'Category updated successfully.']);
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
