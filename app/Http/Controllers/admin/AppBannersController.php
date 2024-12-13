<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountType;
use App\Models\ActivityType;
use App\Models\Categories;
use App\Http\Controllers\Controller;
use App\Models\AppBanner;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\Vendor\VendorPackage;
use App\Models\Vendor\VendorPortfolio;
use App\Models\Vendor\VendorUserDetail;
use Carbon\Carbon;

class AppBannersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!get_user_permission('masters_app_banners', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "App Banners";
        $banners = AppBanner::orderByRaw('COALESCE(updated_at, created_at) DESC')
        ->get();

        return view('admin.app_banners.list', compact('page_heading', 'banners'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!get_user_permission('masters_app_banners', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Create App Banner";
        $id = "";
        $name = "";
        $active = "1";
        $banner_image = "";
        return view("admin.app_banners.create", compact('page_heading', 'id', 'name', 'banner_image', 'active'));
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
            'banner_image' => $request->id == "" ? 'required' : '',
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $ins = [
                'name' => $request->name,
                'active' => $request->active,
            ];

            if ($request->file("banner_image")) {
                $response = image_upload($request, 'app_banners', 'banner_image');

                if ($response['status']) {
                    $ins['banner_image'] = $response['link'];
                }
            }
            if ($request->id != "") {
                $banner = AppBanner::find($request->id);
                $banner->update($ins);
                $status = "1";
                $message = "Banner updated succesfully";
            } else {
                AppBanner::create($ins);
                $status = "1";
                $message = "Banner added successfully";
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$activity_types = ActivityType::select('id','name as activity_name')->where(['deleted' => 0, 'account_id' => AccountType::COMMERCIAL_CENTER])->get();
        if (!get_user_permission('masters_app_banners', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $banner = AppBanner::find($id);
        if ($banner) {
            $page_heading = "App Banner ";
            $mode = "Edit App Banner";
            $id = $banner->id;
            $name = $banner->name;
            $active = $banner->active;
            $banner_image = $banner->banner_image;;
            return view("admin.app_banners.create", compact('page_heading', 'id', 'name', 'active', 'banner_image'));
        } else {
            abort(404);
        }
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
    public function destroy(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];

        $validator = Validator::make($request->all(), [
            'itemId' => 'required',
        ]);

        // If the current user is not user_type 1 then return error
        if (auth()->user()->user_type_id != 1) {
            $status = "0";
            $message = "Only admin user can perform this action";
            return json_encode(['status' => $status, 'message' => $message]);
        }


        if ($validator->fails()) {

            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
        }

        $id = $request->itemId;

        $banner = AppBanner::find($id);
        if ($banner) {
            // Hard delete the record
            $banner->delete();
            $status = "1";
            $message = "Banner removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        return json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }



    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (AppBanner::where('id', $request->id)->update(['active' => $request->status])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }

    public function sort(Request $request)
    {
        if ($request->ajax()) {
            $status = 0;
            $message = '';

            $items = $request->items;
            $items = explode(",", $items);
            $sorted = Categories::sort_item($items);
            if ($sorted) {
                $status = 1;
            }

            echo json_encode(['status' => $status, 'message' => $message]);
        } else {
            $page_heading = "Sort Categories";

            $list = Categories::where(['deleted' => 0, 'parent_id' => 0])->orderBy('sort_order', 'asc')->get();
            $back = url("admin/category");
            return view("admin.sort", compact('page_heading', 'list', 'back'));
        }
    }


    // api get banners without paginations
    public static function apiGetAppBanners(Request $request)
    {

        $limit = $request->limit ? $request->limit : 10;
        $status = "0";
        $message = "";
        $o_data = [];
        $banners = AppBanner::select(["id", "name", "banner_image"])->where(['active' => 1])->orderBy('sort_order', 'asc')->get();
        if ($banners) {
            $status = "1";
            $message = "Banners fetched successfully";
            $o_data = $banners;
        } else {
            $message = "No Banner found";
        }

        $o_data = convert_all_elements_to_string($o_data->toArray());

        return response()->success($message, $o_data);
    }

}
