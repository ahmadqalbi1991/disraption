<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HearAboutUs;
use Illuminate\Http\Request;
use Validator;


class HearAboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!get_user_permission('country','r')) {
        //     return redirect()->route('admin.restricted_page');
        // }
        $page_heading = "Where did you hear about us?";
        $entries = HearAboutUs::where(['deleted' => 0])->orderBy('id', 'desc')->get();
        // dd($countries);
        return view('admin.hearAboutUs.list', compact('page_heading', 'entries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!get_user_permission('country','c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Where did you hear about us";
        $mode = "create";
        $id = "";
        $prefix = "";
        $name = "";
        $dial_code = "";
        $image = "";
        $active = "1";
        return view("admin.hearAboutUs.create", compact('page_heading', 'mode', 'id', 'name', 'dial_code', 'active','prefix'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if (!get_user_permission('country','u')) {
        //     return redirect()->route('admin.restricted_page');
        // }
        $entry = HearAboutUs::find($id);
        if ($entry) {
            $page_heading = "Where did you hear about us";
            $mode = "edit";
            $id = $entry->id;
            $name = $entry->name;
            $active = $entry->active;
            return view("admin.hearAboutUs.create", compact('page_heading', 'mode', 'id', 'name', 'active'));
        } else {
            abort(404);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();
            $check_exist = HearAboutUs::where(['deleted' => 0, 'name' => $request->name])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'name' => $request->name,
                    'prefix' => strtoupper($request->prefix),
                    'dial_code' => $request->dial_code,
                    'active' => $request->active,
                ];

                if ($request->id != "") {
                    $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                    $entry = HearAboutUs::find($request->id);
                    $entry->update($ins);
                    $status = "1";
                    $message = "Entry updated succesfully";
                } else {
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    HearAboutUs::create($ins);
                    $status = "1";
                    $message = "Entry added successfully";
                }
            } else {
                $status = "0";
                $message = "Name should be unique";
                $errors['name'] = $request->name . " already added";
            }

        }
        
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $entry = HearAboutUs::find($id);
        if ($entry) {
            $entry->deleted = 1;
            $entry->active = 0;
            $entry->save();
            $status = "1";
            $message = "Entry removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
}
