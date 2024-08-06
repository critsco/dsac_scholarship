<?php

namespace App\Http\Controllers;

use App\Models\FacultyLoad;
use App\Models\Form;
use App\Models\FormQuestionAnswer;
use App\Models\FormUserRole;
use App\Models\Schedule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $created_at_formatted = "DATE_FORMAT(forms.created_at, '%m-%d-%Y')";
        $schooL_year = "(SELECT CONCAT(`sy_from`, '-', `sy_to`) FROM ref_school_years WHERE ref_school_years.id = forms.school_year_id)";
        $semester = "(SELECT semester FROM ref_semesters WHERE ref_semesters.id = forms.semester_id)";

        $data = Form::select([
            "forms.*",
            DB::raw("$created_at_formatted created_at_formatted"),
            DB::raw("$schooL_year school_year"),
            DB::raw("$semester semester")
        ])
            ->with([
                'form_question_categories' => function ($q) use ($request) {
                    $q->orderBy('order_no', 'asc');
                    $q->with([
                        'form_questions' => function ($q2) use ($request) {
                            if ($request->from === 'SurveyMobile') {
                                $q2->where('status', 1);
                            }
                            $q2->orderBy('order_no', 'asc');
                            $q2->with([
                                'form_question_options' => function ($q3) {
                                    $q3->orderBy('id', 'asc');
                                }
                            ]);
                        }
                    ]);
                },
                'form_user_roles' => function ($q) {
                    $q->where('status', 1);
                    $q->with([
                        'user_role'
                    ]);
                }
            ]);

        $data->where(function ($query) use ($request, $created_at_formatted, $schooL_year, $semester) {
            if ($request->search) {
                $query->orWhere("form_name", "LIKE", "%$request->search%");
                $query->orWhere(DB::raw("$created_at_formatted"), "LIKE", "%$request->search%");
                $query->orWhere(DB::raw("$schooL_year"), "LIKE", "%$request->search%");
                $query->orWhere(DB::raw("$semester"), "LIKE", "%$request->search%");
            }
        });

        if ($request->sort_field && $request->sort_order) {
            if (
                $request->sort_field != "" && $request->sort_field != "undefined" && $request->sort_field != "null"  &&
                $request->sort_order != ""  && $request->sort_order != "undefined" && $request->sort_order != "null"
            ) {
                if ($request->sort_field === 'created_at_formatted') {
                    $data->orderBy(DB::raw("created_at"), isset($request->sort_order)  ? $request->sort_order : 'desc');
                } else {
                    $data->orderBy(isset($request->sort_field) ? $request->sort_field : 'id', isset($request->sort_order)  ? $request->sort_order : 'desc');
                }
            }
        } else {
            $data->orderBy("id", "desc");
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
            "data"      => $data,
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
            "message" => "Data not created",
            "request" => $request->all(),
        ];

        $request->validate([
            "form_name" => "required",
            "school_year_id" => "required",
            "semester_id" => "required",
            "user_role_ids" => "required"
        ]);

        $data = Form::updateOrCreate(
            [
                "id" => $request->id ? $request->id : null
            ],
            [
                "form_name" => $request->form_name,
                "school_year_id" => $request->school_year_id,
                "semester_id" => $request->semester_id,
                "created_by" => auth()->user()->id
            ]
        );

        if ($data) {
            FormUserRole::where("form_id", $data->id)->update(["status" => 0]);

            foreach ($request->user_role_ids as $key => $value) {
                $dataUserRole = FormUserRole::where("form_id", $data->id)->where("user_role_id", $value)->first();

                if ($dataUserRole) {
                    $dataUserRole->fill(["status" => 1])->save();
                } else {
                    FormUserRole::create([
                        "form_id" => $data->id,
                        "user_role_id" => $value,
                        "status" => 1,
                        "created_by" => auth()->user()->id
                    ]);
                }
            }

            $ret = [
                "success" => true,
                "message" => "Data created successfully",
                "id"      => $data->id
            ];
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ret = [
            "success" => false,
            "message" => "No data found"
        ];

        $data = Form::with([
            'form_question_categories' => function ($q) {
                $q->where('status', 1);
                $q->orderBy('order_no', 'asc');
                $q->with([
                    'form_questions' => function ($q2) {
                        $q2->where('status', 1);
                        $q2->orderBy('order_no', 'asc');
                        $q2->with([
                            'form_question_options' => function ($q3) {
                                $q3->orderBy('id', 'asc');
                            }
                        ]);
                    }
                ]);
            }
        ])
            ->find($id);

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
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $data = Form::find($id);

        if ($data) {
            $data->form_question_categories()->delete();
            $data->form_user_roles()->delete();
            $data->delete();

            $ret = [
                "success" => true,
                "message" => "Data deleted successfully"
            ];
        }

        return response()->json($ret, 200);
    }

    public function form_change_status(Request $request)
    {
        $ret = [
            "success" => false,
            "message" => "Data status not change"
        ];

        $data = Form::find($request->id);

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

    public function mobile_student_form(Request $request)
    {
        $created_at_formatted = "DATE_FORMAT(forms.created_at, '%m-%d-%Y')";
        $schooL_year = "(SELECT CONCAT(`sy_from`, '-', `sy_to`) FROM ref_school_years WHERE ref_school_years.id = forms.school_year_id)";
        $semester = "(SELECT semester FROM ref_semesters WHERE ref_semesters.id = forms.semester_id)";

        $data = Form::select([
            "forms.*",
            DB::raw("$created_at_formatted created_at_formatted"),
            DB::raw("$schooL_year school_year"),
            DB::raw("$semester semester")
        ])
            ->with([
                'form_question_categories' => function ($q) use ($request) {
                    $q->where('status', 1);
                    $q->orderBy('order_no', 'asc');
                    $q->with([
                        'form_questions' => function ($q2) use ($request) {
                            if ($request->from === 'SurveyMobile') {
                                $q2->where('status', 1);
                            }
                            $q2->orderBy('order_no', 'asc');
                            $q2->with([
                                'form_question_options' => function ($q3) {
                                    $q3->orderBy('id', 'asc');
                                }
                            ]);
                        }
                    ]);
                },
                'form_user_roles' => function ($q) {
                    $q->where('status', 1);
                    $q->with([
                        'user_role'
                    ]);
                }
            ]);

        $data->where('status', 1)
            ->whereHas('form_user_roles', function ($q) {
                $q->where('user_role_id', auth()->user()->user_role_id);
                $q->where('status', 1);
            });

        $data = $data->orderBy("id", "desc")->get();

        $find_profile_by_user_id = $this->find_profile_by_user_id(auth()->user()->id);

        $data = collect($data)->map(function ($item) use ($request, $find_profile_by_user_id) {
            $fullname = "(SELECT TRIM(CONCAT_WS(' ', firstname, IF(middlename='', NULL, middlename), lastname, IF(name_ext='', NULL, name_ext))) FROM profiles WHERE profiles.id = faculty_loads.profile_id)";
            $faculties = FacultyLoad::select([
                "faculty_loads.*",
                DB::raw("$fullname fullname")
            ])
                ->where('school_year_id', $item->school_year_id)
                ->where('semester_id', $item->semester_id)
                ->get();

            $item["faculties"] = $faculties;

            $subjects = Schedule::select([
                "subject_id",
                "section_id",
                DB::raw("(SELECT code FROM ref_subjects WHERE ref_subjects.id = schedules.subject_id) subject_code"),
            ])
                ->where("school_year_id", $item->school_year_id)
                ->where("semester_id", $item->semester_id)
                ->where("student_id", $find_profile_by_user_id->id)
                ->where(DB::raw("(SELECT COUNT(*) FROM form_question_answers WHERE form_question_answers.subject_id = schedules.subject_id AND form_question_answers.profile_id = schedules.student_id AND form_question_answers.form_id = $item->id)"), 0)
                ->get();

            $item["subjects"] = $subjects;

            return $item;
        });

        return response()->json([
            "success"   => true,
            "data"      => $data,
        ], 200);
    }

    public function evaluation_print($id)
    {
        $dataForm = Form::with([
            'form_question_categories' => function ($q) {
                $q->where('status', 1);
                $q->orderBy('order_no', 'asc');
                $q->with([
                    'form_questions' => function ($q2) {
                        $q2->where('status', 1);
                        $q2->orderBy('order_no', 'asc');
                        $q2->with([
                            'form_question_options' => function ($q3) {
                                $q3->orderBy('id', 'asc');
                                $q3->orderBy('order_no', 'asc');
                            },
                        ]);
                    }
                ]);
            }
        ])
            ->find($id);

        $school_year = $this->schoolYearActive();

        $deparment = "(SELECT department_name FROM ref_departments WHERE ref_departments.id = faculty_loads.department_id)";
        $fullname = "(SELECT TRIM(CONCAT_WS(' ', firstname, IF(middlename='', NULL, middlename), lastname, IF(name_ext='', NULL, name_ext))) FROM profiles WHERE profiles.id = faculty_loads.profile_id)";
        $department_name = "(SELECT (SELECT (SELECT department_name FROM ref_departments WHERE ref_departments.id = profile_departments.department_id) FROM profile_departments WHERE profile_departments.profile_id = profiles.id AND profile_departments.`status`=1 ORDER BY id LIMIT 1) FROM profiles WHERE profiles.id = faculty_loads.profile_id)";

        $dataFaculty = FacultyLoad::select([
            "faculty_loads.*",
            DB::raw("$deparment department"),
            DB::raw("$fullname fullname"),
            DB::raw("$department_name department_name")
        ])
            ->with([
                'form_question_answers' => function ($q) use ($dataForm) {
                    $q->where('form_id', $dataForm->id);
                    $q->orderBy('id', 'asc');
                }
            ])
            ->where('school_year_id', $school_year->id)
            ->limit(5)
            ->get();

        $ret = [
            "dataForm" => $dataForm,
            "dataFaculty" => $dataFaculty,
            "school_year" => $school_year
        ];

        $pdf = Pdf::loadView('pdf.summary-of-performance-appraisal-ratings-template', $ret);

        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed' => TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        $pdf->setPaper('LEGAL', 'portrait');

        return $pdf->stream('summary-of-performance-appraisal-ratings.pdf');
        // return view('pdf.summary-of-performance-appraisal-ratings-template', $ret);
    }
}
