<?php

namespace App\Http\Controllers;

use App\Models\SystemLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $created_at_formatted = "DATE_FORMAT(created_at, '%m-%d-%Y')";
        $data = SystemLink::select([
            "*",
            DB::raw("$created_at_formatted created_at_formatted"),
        ])->with("attachments");

        $data = $data->where(function ($query) use ($request, $created_at_formatted) {
            if ($request->search) {
                $query->orWhere("name", 'LIKE', "%$request->search%");
                $query->orWhere("description", 'LIKE', "%$request->search%");
                $query->orWhere("url", 'LIKE', "%$request->search%");
                $query->orWhere(DB::raw("($created_at_formatted)"), 'LIKE', "%$request->search%");
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
            $data = $data->paginate($request->page_size, ['*'], 'page', $request->page)->toArray();
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
        $ret = [
            'success' => false,
            'message' => 'Data not saved successfully',
        ];

        $data = $request->validate([
            'name'          => 'required',
            'url'           => 'required',
            'date_created'  => 'required'
        ]);

        $data += [
            "description"   => $request->description,
        ];

        $systemLink = SystemLink::updateOrCreate(["id" => $request->id ? $request->id : null], $data);

        if ($systemLink) {
            if ($request->hasFile('logo_file')) {
                $logo_file = $request->file('logo_file');

                $this->create_attachment($systemLink, $logo_file, [
                    "action" => "Add",
                    "folder_name" => "system_links/system_link-" . $systemLink->id,
                    "file_description" => "System Logo",
                    "file_type" => "image",
                ]);
            }

            $ret = [
                'success' => true,
                'message' => 'Data saved successfully',
            ];
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemLink  $systemLink
     * @return \Illuminate\Http\Response
     */
    public function show(SystemLink $systemLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SystemLink  $systemLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemLink $systemLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemLink  $systemLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemLink $systemLink)
    {
        //
    }

    public function system_link_archived(Request $request)
    {
        $ret = [
            'success' => false,
            'message' => 'Data not archived',
        ];

        $request->validate([
            'ids' => 'required',
        ]);

        $count = 0;

        foreach ($request->ids as $key => $value) {
            $systemLink = SystemLink::find($value);

            if ($systemLink->delete()) {
                $count++;
            }
        }

        if ($count > 0) {
            $ret = [
                'success' => true,
                'message' => 'Data archived successfully',
            ];
        }

        return response()->json($ret, 200);
    }
}
