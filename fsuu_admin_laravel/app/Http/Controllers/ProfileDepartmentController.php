<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\ProfileDepartment;
use Illuminate\Http\Request;

class ProfileDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataQuery = ProfileDepartment::query();

        if (isset($request->status)) {
            $dataQuery->where("status", $request->status);
        }

        if (isset($request->profile_id)) {
            $dataQuery->where('profile_id', $request->profile_id);
        }

        $data = $dataQuery->get();

        return response()->json([
            "success" => true,
            "data" => $data,
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
            "message" => "Failed to save department",
            "request" => $request->all()
        ];

        if (count($request->department_ids) > 0 && $request->profile_id) {
            ProfileDepartment::where('profile_id', $request->profile_id)->update(['status' => 0]);

            foreach ($request->department_ids as $key => $value) {
                $findProfileDepartment = ProfileDepartment::where('profile_id', $request->profile_id)
                    ->where('department_id', $value['department_id'])
                    ->first();

                if ($findProfileDepartment) {
                    $findProfileDepartment->status = $value['status'];
                    $findProfileDepartment->updated_by = auth()->user()->id;
                    $findProfileDepartment->save();
                } else {
                    ProfileDepartment::create([
                        'profile_id' => $request->profile_id,
                        'department_id' => $value['department_id'],
                        'status' => $value['status'],
                        'created_by' => auth()->user()->id,
                    ]);
                }
            }

            $ret = [
                "success" => true,
                "message" => "Successfully saved department",
            ];
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProfileDepartment  $profileDepartment
     * @return \Illuminate\Http\Response
     */
    public function show(ProfileDepartment $profileDepartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProfileDepartment  $profileDepartment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProfileDepartment $profileDepartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProfileDepartment  $profileDepartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProfileDepartment $profileDepartment)
    {
        //
    }
}