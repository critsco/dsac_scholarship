<?php

namespace App\Http\Controllers;

use App\Models\RefFloor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefFloorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = RefFloor::where(function ($query) use ($request) {
            if ($request->search) {
                $query->orWhere('floor', 'LIKE', "%$request->search%");
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
            'floor' => [
                'required',
                Rule::unique('ref_floors')->ignore($request->id)
            ],
        ]);

        $data = [
            "floor" => $request->floor,
        ];

        $floor = RefFloor::updateOrCreate([
            "id" => $request->id,
        ], $data);

        if ($floor) {
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
     * @param  \App\Models\RefFloor  $refFloor
     * @return \Illuminate\Http\Response
     */
    public function show(RefFloor $refFloor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefFloor  $refFloor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefFloor $refFloor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefFloor  $refFloor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findFloor = RefFloor::find($id);

        if ($findFloor) {
            if ($findFloor->delete()) {
                $ret = [
                    "success" => true,
                    "message" => "Data deleted successfully"
                ];
            }
        }
        return response()->json($ret, 200);
    }
}
