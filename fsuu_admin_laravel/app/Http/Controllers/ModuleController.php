<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataQuery = Module::with(['module_buttons']);

        if ($request->has("system_id")) {
            $dataQuery->where('system_id', $request->system_id);
        }

        if ($request->sort_field && $request->sort_order) {
            if (
                $request->sort_field != '' && $request->sort_field != 'undefined' && $request->sort_field != 'null'  &&
                $request->sort_order != ''  && $request->sort_order != 'undefined' && $request->sort_order != 'null'
            ) {
                $dataQuery->orderBy(isset($request->sort_field) ? $request->sort_field : 'module_code', isset($request->sort_order)  ? $request->sort_order : 'asc');
            }
        } else {
            $dataQuery->orderBy('module_code', 'asc');
        }

        if ($request->page_size) {
            $data = $dataQuery->limit($request->page_size)
                ->paginate($request->page_size, ['*'], 'page', $request->page)
                ->toArray();
        } else {
            $data = $dataQuery->get();
        }

        return response()->json([
            "success" => true,
            "data" => $data,
            "request" => $request->all()
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
            "message" => "Module not " . ($request->id ? "updated" : "created"),
            "request" => $request->all()
        ];

        $dataModule = Module::updateOrCreate([
            "id" => $request->id ? $request->id : null
        ], [
            "module_code" => $request->module_code,
            "module_name" => $request->module_name,
            "description" => $request->description,
            "system_id"   => $request->system_id,
        ]);

        if ($dataModule) {

            if ($request->module_buttons) {
                if (count($request->module_buttons) > 0) {
                    foreach ($request->module_buttons as $key => $value) {

                        if (!empty($value['id'])) {
                            $existingButton = $dataModule->module_buttons()->findOrFail($value['id']);
                            $existingButton->update($value);
                        } else {
                            $dataModule->module_buttons()->create($value);
                        }
                    }
                }
            }

            $ret = [
                "success" => true,
                "message" => "Module " . ($request->id ? "updated" : "created"),
            ];
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        //
    }
}
