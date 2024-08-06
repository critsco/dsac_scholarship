<?php

namespace App\Http\Controllers;

use App\Models\FormQuestion;
use App\Models\FormQuestionCategory;
use App\Models\FormQuestionOption;
use Illuminate\Http\Request;

class FormQuestionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = FormQuestionCategory::where(function ($query) use ($request) {
            if ($request->search) {
                $query->orWhere("category", "LIKE", "%$request->search%");
                $query->orWhere("visible", "LIKE", "%$request->search%");
                // $query->orWhere("order_no", "LIKE", "%$request->search%");
            }
        });

        if ($request->form_id) {
            $data = $data->where('form_id', $request->form_id);
        }

        if ($request->sort_field && $request->sort_order) {
            if (
                $request->sort_field != "" && $request->sort_field != "undefined" && $request->sort_field != "null"  &&
                $request->sort_order != ""  && $request->sort_order != "undefined" && $request->sort_order != "null"
            ) {
                $data = $data->orderBy(isset($request->sort_field) ? $request->sort_field : 'id', isset($request->sort_order)  ? $request->sort_order : 'desc');
            }
        } else {
            $data = $data->orderBy("order_no", "asc");
        }

        if ($request->page_size) {
            $data = $data->limit($request->page_size)
                ->paginate($request->page_size, ["*"], "page", $request->page)
                ->toArray();
        } else {
            $data = $data->get();
        }

        return response()->json([
            "success"   => true,
            "data"      => $data
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
            "message" => "Data not " . ($request->id ? "updated" : "saved")
        ];

        $request->validate([
            'form_id'   => 'required',
            'category'  => 'required',
            'form_questions' => 'required',
        ]);

        $lastQuestionCategory = FormQuestionCategory::where('form_id', $request->form_id)->orderBy('order_no', 'desc')->first();

        $questionCategoryData = [
            "form_id"   => $request->form_id,
            "category"  => $request->category,
        ];

        if ($request->id) {
            $questionCategoryData['updated_by'] = auth()->user()->id;
        } else {
            $questionCategoryData['created_by'] = auth()->user()->id;
            $questionCategoryData['order_no'] = $lastQuestionCategory ? $lastQuestionCategory->order_no + 1 : 0;
        }

        $createdFormQuestionCategory = FormQuestionCategory::updateOrCreate([
            "id" => $request->id
        ], $questionCategoryData);

        if ($createdFormQuestionCategory) {
            $form_question_category_id = $createdFormQuestionCategory->id;

            if ($request->form_questions) {
                foreach ($request->form_questions as $key => $value) {
                    $dataFormQuestions = [
                        "form_question_category_id" => $form_question_category_id,
                        "question_code"             => $this->addLeadingZero($key + 1, 2),
                        "question"                  => $value['question'],
                        "question_tips"             => $value['question_tips'] ?? "",
                        "description"               => $value['description'] ?? "",
                        "option_label"              => $value['option_label'] ?? "",
                        "question_type"             => $value['question_type'],
                        "with_attachment"           => $value['with_attachment'],
                        "required"                  => $value['required'],
                        "max_checkbox"              => isset($value['max_checkbox']) ? $value['max_checkbox'] : null,
                        "status"                   => 1,
                        "order_no"                  => $key,
                    ];

                    if ($value['id']) {
                        $dataFormQuestions['updated_by'] = auth()->user()->id;
                    } else {
                        $dataFormQuestions['created_by'] = auth()->user()->id;
                    }

                    $createdFormQuestion = FormQuestion::updateOrCreate([
                        "id" => !empty($value['id']) ? $value['id'] : null
                    ], $dataFormQuestions);

                    if ($createdFormQuestion) {
                        if (!empty($value['form_question_options'])) {
                            foreach ($value['form_question_options'] as $key2 => $value2) {
                                $dataQuestionOption = [
                                    "form_question_id"  => $createdFormQuestion->id,
                                    'option_code'       => $this->addLeadingZero($key2 + 1, 2),
                                    "option"            => $value2['option'],
                                    "scale"             => $value2['scale'],
                                    "order_no"          => $key2,
                                ];

                                if ($value2['id']) {
                                    $dataQuestionOption['updated_by'] = auth()->user()->id;
                                } else {
                                    $dataQuestionOption['created_by'] = auth()->user()->id;
                                }

                                FormQuestionOption::updateOrCreate([
                                    "id" => !empty($value2['id']) ? $value2['id'] : null
                                ], $dataQuestionOption);
                            }
                        }
                    }
                }
            }

            $ret = [
                "success" => true,
                "message" => "Data " . ($request->id ? "updated" : "saved") . " successfully"
            ];
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormQuestionCategory  $formQuestionCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ret = [
            "success" => false,
            "message" => "No data found"
        ];

        $data = FormQuestionCategory::with([
            "form",
            "form_questions" => function ($q) {
                $q->with([
                    'form_question_options',
                    'form_question_answers'
                ])->orderBy('order_no', 'asc');
            }
        ])->find($id);

        if ($data) {
            $ret = [
                "success" => true,
                "message" => "Data found",
                "data"    => $data
            ];
        }

        return response()->json($ret, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormQuestionCategory  $formQuestionCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormQuestionCategory $formQuestionCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormQuestionCategory  $formQuestionCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret  = [
            "success" => false,
            "message" => "Data not deleted",
        ];

        $data = FormQuestionCategory::find($id);

        if ($data) {
            if ($data->delete()) {
                $data->deleted_by = auth()->user()->id;
                $data->save();

                $ret  = [
                    "success" => true,
                    "message" => "Data deleted successfully",
                ];
            }
        }

        return response()->json($ret, 200);
    }

    public function form_question_category_order(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Sort order not updated",
        ];

        if ($request->data) {
            foreach ($request->data as $key => $value) {
                $data = FormQuestionCategory::find($value['id']);
                $data->fill(['order_no' => $key])->save();
            }

            $ret = [
                "success" => true,
                "message" => "Data sort order updated successfully",
            ];
        }

        return response()->json($ret, 200);
    }

    public function form_question_category_change_status(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data status not change"
        ];

        $data = FormQuestionCategory::find($request->id);

        if ($data) {
            $dataUpdate = $data->fill(['status' => $data->status == 1 ? 0 : 1])->save();

            if ($dataUpdate) {
                $ret = [
                    "success" => true,
                    "message" => "Data status changed successfully",
                ];
            }
        }

        return response()->json($ret, 200);
    }

    public function form_question_category_view_result($id)
    {
        $ret = [
            "success" => false,
            "message" => "No data found"
        ];

        $data = FormQuestionCategory::with([
            "form",
            "form_questions" => function ($q) {
                $q->with([
                    'form_question_options',
                    'form_question_answers',
                ])
                    ->orderBy('order_no', 'asc');
            }
        ])->find($id);

        if ($data) {
            $ret = [
                "success" => true,
                "message" => "Data found",
                "data"    => $data
            ];
        }

        return response()->json($ret, 200);
    }
}
