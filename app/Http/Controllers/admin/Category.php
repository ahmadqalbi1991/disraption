<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountType;
use App\Models\ActivityType;
use App\Models\Categories;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\Vendor\VendorPackage;
use App\Models\Vendor\VendorPortfolio;
use App\Models\Vendor\VendorUserDetail;
use Carbon\Carbon;

class Category extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!get_user_permission('masters_category', 'r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Categories";
        $categories = Categories::orderBy('sort_order', 'asc')->get();

        return view('admin.category.list', compact('page_heading', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!get_user_permission('masters_category','c')) {
            return redirect()->route('admin.restricted_page');
        }


        // $activity_types = ActivityType::select('id','name as activity_name')->where(['deleted' => 0, 'account_id' => AccountType::COMMERCIAL_CENTER])->get();
        $page_heading = "Create Category";
        $id = "";
        $name = "";
        $parent_id = "";
        $image = "";
        $active = "1";
        $banner_image = "";
        $category = [];
        $categories = Categories::where(['active' => 1])->get();
        return view("admin.category.create", compact('page_heading', 'category', 'id', 'name', 'image', 'active', 'categories'));
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
            'image' => $request->id ? '' : 'required',
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();
            $check_exist = Categories::where(['name' => $request->name])->where('id', '!=', $request->id)->get()->toArray();

            if (empty($check_exist)) {
                $ins = [
                    'name' => $request->name,
                    'active' => $request->active,
                ];

                if ($request->file("image")) {
                    $response = image_upload($request, 'category', 'image');
                    if ($response['status']) {
                        $ins['image'] = $response['link'];
                    }
                }
                if ($request->id != "") {
                    $category = Categories::find($request->id);
                    $category->update($ins);
                    $status = "1";
                    $message = "Category updated succesfully";
                } else {
                    Categories::create($ins);
                    $status = "1";
                    $message = "Category added successfully";
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$activity_types = ActivityType::select('id','name as activity_name')->where(['deleted' => 0, 'account_id' => AccountType::COMMERCIAL_CENTER])->get();
        if (!get_user_permission('masters_category', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $category = Categories::find($id);
        if ($category) {
            $page_heading = "Edit Category ";
            $id = $category->id;
            $name = $category->name;
            $image = $category->image;
            $active = $category->active;
            $parent_id = '';
            $banner_image = '';
            //$activity_id = $category->activity_id;
            $categories = Categories::where('id', '!=', $id)->get();
            return view("admin.category.create", compact('page_heading', 'category', 'id', 'name', 'parent_id', 'image', 'active', 'categories', 'banner_image'));
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


        $vendor = User::whereHas('vendor_details', function($query) use ($id) {
            $query->where('category_id', $id);
        })->exists();


        // check if this category is used in any then returnt error
        if ($vendor) {
            $status = "0";
            $message = "You cannot delete this category as it is being used by a vendor.";
            return json_encode(['status' => $status, 'message' => $message]);
        }



        $category = Categories::find($id);
        if ($category) {
            // Hard delete the record
            $category->delete();

            $status = "1";
            $message = "Category removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        return json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }



    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (Categories::where('id', $request->id)->update(['active' => $request->status])) {
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

            $list = Categories::orderBy('sort_order', 'asc')->get();
            $back = url("admin/category");
            return view("admin.sort", compact('page_heading', 'list', 'back'));
        }
    }


    // api get cateogories with pagination
    public function apiGetCategories(Request $request)
    {

        $limit = $request->limit ? $request->limit : 10;

        $status = "0";
        $message = "";
        $o_data = [];
        $categories = Categories::select(["id", "name", "image"])->where(['active' => 1])->orderBy('sort_order', 'asc')->paginate($limit);
        if ($categories) {
            $status = "1";
            $message = "Categories fetched successfully";
            $o_data = $categories;
        } else {
            $message = "No categories found";
        }

        $o_data = convert_all_elements_to_string($o_data->toArray());
       $o_data = cleanPaginationResultArray($o_data);

        return response()->success($message, $o_data);
    }



}
