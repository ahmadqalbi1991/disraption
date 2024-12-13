<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingResource;
use App\Models\CountryModel;
use App\Models\Vendor\VendorBookingDate;
use Illuminate\Http\Request;
use Validator;
use function Webmozart\Assert\Tests\StaticAnalysis\object;

class BookingResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!get_user_permission('masters_booking_resources', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Workstations";
        $items = BookingResource::getAll();

        return view('admin.booking_resources.list', compact('page_heading', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!get_user_permission('masters_booking_resources', 'c')) {
            return redirect()->route('admin.restricted_page');
        }


        $page_heading = "Create Workstation";
        $id = "";
        $name = "";
        $active = "1";
        return view("admin.booking_resources.create", compact('page_heading', 'id', 'name','active'));
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

            // $request->name = strtolower($request->name);

            $input = $request->all();
            $check_exist = BookingResource::where(['deleted' => 0])->whereRaw('LOWER(name) = ?',[strtolower($request->name)])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'name' => $request->name,
                    'active' => $request->active ?? 1,
                ];

                if ($request->id != "") {
                    $item = BookingResource::find($request->id);
                    $item->update($ins);
                    $status = "1";
                    $message = "Resource updated succesfully";
                } else {
                    BookingResource::create($ins);
                    $status = "1";
                    $message = "Resource added successfully";
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!get_user_permission('masters_booking_resources', 'u')) {
            return redirect()->route('admin.restricted_page');
        }


        $item = BookingResource::find($id);
        if ($item) {
            $page_heading = "Edit Workstation";
            $id = $item->id;
            $name = $item->name;
            $active = $item->active;
            return view("admin.booking_resources.create", compact('page_heading', 'id', 'name', 'active'));
        } else {
            abort(404);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'itemId' => 'required',
        ]);

        if ($validator->fails()) {
            // get first error
            return response()->error($validator->errors()->first());
        }

        // If the current user is not user_type 1 then return error
        if (auth()->user()->user_type_id != 1) {
            $status = "0";
            $message = "Only admin user can perform this action";
            return json_encode(['status' => $status, 'message' => $message]);
        }

        $id = $request->itemId;

        $item = BookingResource::find($id);

        // if not item found
        if (!$item) {
            return response()->error('Resource not found');
        }

        // Check if VendorBookingDate have this item in booking_id column
        $check = VendorBookingDate::where('resource_id', $id)->first();

        // if it have then return error that we can delete
        if ($check) {
            return response()->error('You can not delete this resource because it is used in booking');
        }

        // Delete the item
        $item->update(['deleted' => 1]);

        return response()->success('Resource deleted successfully');


    }

}
