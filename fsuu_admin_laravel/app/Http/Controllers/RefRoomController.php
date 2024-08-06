<?php

namespace App\Http\Controllers;

use App\Models\RefRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RefRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $building = "SELECT building FROM ref_buildings WHERE ref_buildings.id = ref_rooms.building_id";
        $floor = "SELECT floor FROM ref_floors WHERE ref_floors.id = ref_rooms.floor_id";

        $data = RefRoom::select([
            '*',
            DB::raw("($building) building"),
            DB::raw("($floor) floor"),
        ]);

        $data = $data->where(function ($query) use ($request, $floor, $building) {
            if ($request->search) {
                $query->orWhere('room_code', 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($floor)"), 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($building)"), 'LIKE', "%$request->search%");
            }
        });

        if ($request->building_id) {
            $data = $data->where(function ($query) use ($request) {
                $query->orWhere("building_id", $request->building_id);
                $query->orWhereNull("building_id");
            });
        }

        if ($request->floor_id) {
            $data = $data->where(function ($query) use ($request) {
                $query->orWhere("floor_id", $request->floor_id);
                $query->orWhereNull("floor_id");
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
            'building_id' => ['required'],
            'floor_id' => ['required'],
            'room_code' => [
                'required',
                Rule::unique('ref_rooms')->where(function ($query) use ($request) {
                    return $query->where('building_id', $request->building_id)
                        ->where('floor_id', $request->floor_id);
                })->ignore($request->id)
            ],
            'max_slot' => ['required'],
        ]);

        $data = [
            "room_code" => $request->room_code,
            "building_id" => $request->building_id,
            "floor_id" => $request->floor_id,
            "max_slot" => $request->max_slot,
        ];

        $room = RefRoom::updateOrCreate([
            "id" => $request->id,
        ], $data);

        if ($room) {
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
     * @param  \App\Models\RefRoom  $refRoom
     * @return \Illuminate\Http\Response
     */
    public function show(RefRoom $refRoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefRoom  $refRoom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefRoom $refRoom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefRoom  $refRoom
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findRoom = RefRoom::find($id);


        if ($findRoom) {
            if ($findRoom->delete()) {

                $ret = [
                    "success" => true,
                    "message" => "Data deleted successfully"
                ];
            }
        }
        return response()->json($ret, 200);
    }
}
