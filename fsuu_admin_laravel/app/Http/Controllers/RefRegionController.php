<?php

namespace App\Http\Controllers;

use App\Models\RefBarangay;
use App\Models\RefRegion;
use Illuminate\Http\Request;

class RefRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data = RefRegion::where(function ($query) use ($request) {
            if ($request->search) {
                $query->orWhere('region', 'LIKE', "%$request->search%");
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RefRegion  $refRegion
     * @return \Illuminate\Http\Response
     */
    public function show(RefRegion $refRegion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefRegion  $refRegion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefRegion $refRegion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefRegion  $refRegion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = [
            "success" => false,
            "message" => "Data not deleted"
        ];

        $findData = RefRegion::find($id);

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

    public function region_dropdown()
    {
        $data = RefRegion::select('id', 'region')
            ->with(['ref_provinces' => function ($query) {
                $query->select('id', 'region_id', 'province')
                    ->with(['ref_municipalities' => function ($query) {
                        $query->select('id', 'province_id', 'municipality')
                            ->with(['ref_barangays' => function ($query) {
                                $query->select('id', 'municipality_id', 'barangay')
                                    ->orderBy('barangay', 'asc');
                            }])
                            ->orderBy('municipality', 'asc');
                    }])
                    ->orderBy('province', 'asc');
            }])
            ->orderBy('region', 'asc')
            ->get();

        return response()->json([
            'success'   => true,
            'data'      => $data
        ], 200);
    }
}
