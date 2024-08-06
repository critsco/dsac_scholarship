<?php

namespace App\Http\Controllers;

use App\Models\FormQuestionAnswer;
use Illuminate\Http\Request;

class FormQuestionAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            "message" => "Answer not saved.",
        ];

        if ($request->data) {
            $createFormAnswer = FormQuestionAnswer::insert($request->data);

            if ($createFormAnswer) {
                $ret = [
                    "success" => true,
                    "message" => "Answer saved successfully.",
                ];
            } else {
                $ret = [
                    "success" => true,
                    "message" => "Answer not saved.",
                ];
            }
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormQuestionAnswer  $formQuestionAnswer
     * @return \Illuminate\Http\Response
     */
    public function show(FormQuestionAnswer $formQuestionAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormQuestionAnswer  $formQuestionAnswer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormQuestionAnswer $formQuestionAnswer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormQuestionAnswer  $formQuestionAnswer
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormQuestionAnswer $formQuestionAnswer)
    {
        //
    }
}