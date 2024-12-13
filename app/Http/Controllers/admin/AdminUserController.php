<?php

namespace App\Http\Controllers\Admin;

use Hash;
use Validator;
use App\Models\Role;
use App\Models\User;
use App\Models\VendorModel;
use Illuminate\Http\Request;
use App\Models\UserPrivileges;
use App\Models\AdminDesignation;
use App\Models\VendorDetailsModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!get_user_permission('admin_users', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Admin Users";

        $users_db = User::with('user_role')
        ->where(['users.role' => '1'])
        ->where('users.id', '!=', '1');

        

        // -------------- Table ordering ------------
        $disableSortingColumnsIndex = [0,1];
        $tableColumnsIndexMaping = array(
            // 0-> row number,
            // 1-> actions
            2 => 'name',
            3 => 'email',
            4 => 'user_role',
            5 => 'last_login',
            6 => 'created_at',
            7 => 'active',
        );

        // ready the sorting name
        $sort_name = '';
        $sort_order = '';

        // if the request has sort_index and sort_order then set the values
        if ($request->has('sort_index') && $request->has('sort_order')) {
            $sort_name = array_key_exists($request->sort_index, $tableColumnsIndexMaping) ? $tableColumnsIndexMaping[$request->sort_index] : '';
            $sort_order = $request->sort_order;
        }



        if ($sort_name && $sort_order) {

            // ______ If sort name is found then add the select to the query so we can order by the column ____

            switch ($sort_name) {


                case 'user_role':
                    $users_db->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->select('users.*', 'roles.role as role_name')
                    ->orderBy('role_name', $sort_order);
                    break;

                default:
                    // order the queries which can be directly order
                    $users_db->orderBy($sort_name, $sort_order);
                    break;
            }


            // _________________________________________________________________________________________________

        } else {

            // Default sorting
            $users_db->orderByRaw('COALESCE(updated_at, created_at) DESC');
        }


        // ------------------------------------------

       // dd($users_db->toSql(), $users_db->getBindings());

        $datamain = $users_db->get();

        return view('admin.admin_users.list', compact('page_heading', 'datamain', 'disableSortingColumnsIndex'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!get_user_permission('admin_users', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Admin Users";
        $mode = "create";
        $id = "";
        $prefix = "";
        $name = "";
        $dial_code = "";
        $image = "";
        $active = "1";
        $roles  = Role::where(['status' => 1])->orderBy('role', 'asc')->get();

        return view("admin.admin_users.create", compact('page_heading', 'id', 'name', 'dial_code', 'active', 'prefix', 'roles'));
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
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        if (!empty($request->password)) {
            $validator = Validator::make($request->all(), [
                'confirm_password' => 'required',
            ]);
        }
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            // lowercase email, first name and last name
            $request->email = strtolower($request->email);
            $request->first_name = strtolower($request->first_name);
            $request->last_name = strtolower($request->last_name);


            $input = $request->all();
            $check_exist = User::where('email', $request->email)->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {




                $ins = [
                    'name'       => $request->first_name . " " . $request->last_name,
                    'email'      => $request->email,
                    'role'       => '1', // admin
                    'user_type_id' => '1',
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    // generate random phone number
                    'phone'      => '0' . rand(1000000000, 9999999999),
                    'role_id' => $request->designation,
                    'active'      => $request->active,
                    'verified' => '1'
                ];





                if ($request->password) {
                    $ins['password'] = bcrypt($request->password);
                }

                if ($request->file("image")) {
                    $response = image_upload($request, 'company', 'image');
                    if ($response['status']) {
                        $ins['user_image'] = $response['link'];
                    }
                }


                if ($request->id != "") {
                    $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                    $user = User::find($request->id);
                    $user->update($ins);


                    $status = "1";
                    $message = "Admin Users updated succesfully";
                } else {
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    $userid = User::create($ins)->id;

                    $status = "1";
                    $message = "Admin Users added successfully";
                }
            } else {
                $status = "0";
                $message = "Email should be unique";
                $errors['email'] = $request->email . " already added";
            }
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }


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
        if (!get_user_permission('admin_users', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Edit Admin Users";
        $datamain = User::find($id);
        $roles  = Role::where(['status' => 1])->orderBy('role', 'asc')->get();


        if ($datamain) {
            return view("admin.admin_users.create", compact('page_heading', 'datamain', 'id', 'roles'));
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


    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (User::where('id', $request->id)->update(['active' => $request->status])) {
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
    public function verify(Request $request)
    {
        $status = "0";
        $message = "";
        if (User::where('id', $request->id)->update(['verified' => $request->status])) {
            $status = "1";
            $msg = "Successfully verified";
            if (!$request->status) {
                $msg = "Successfully updated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $datatb = User::find($id);
        if ($datatb) {
            $datatb->active = 0;
            $datatb->save();
            // soft delete
            $datatb->delete();

            $status = "1";

            $message = "Admin Users removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }

    public function access_restricted()
    {
        $page_heading = "Access Restricted";
        return view('vendor.access_restricted', compact('page_heading'));
    }


    // Get all users
    public function all_users(Request $request)
    {

        $page_heading = "All Users";
        $users_query = User::leftJoin('vendor_user_details', 'users.id', '=', 'vendor_user_details.user_id');

        // -------------- Table ordering ------------
        $disableSortingColumnsIndex = [0, 1];
        $tableColumnsIndexMaping = array(
            // 0-> row number,
            // 1-> actions
            2 => 'email',
            3 => 'name',
            4 => 'role',
            5 => 'last_login',
            6 => 'created_at',
            7 => 'active',
        );

        // ready the sorting name
        $sort_name = '';
        $sort_order = '';

        // if the request has sort_index and sort_order then set the values
        if ($request->has('sort_index') && $request->has('sort_order')) {
            $sort_name = array_key_exists($request->sort_index, $tableColumnsIndexMaping) ? $tableColumnsIndexMaping[$request->sort_index] : '';
            $sort_order = $request->sort_order;
        }



        if ($sort_name && $sort_order) {

            // ______ If sort name is found then add the select to the query so we can order by the column ____

            switch ($sort_name) {

                default:
                    // order the queries which can be directly order
                    $users_query->orderBy($sort_name, $sort_order);
                    break;
            }


            // _________________________________________________________________________________________________

        } else {
            $users_query->orderBy("id", "desc");
        }


        // ------------------------------------------


        $users = $users_query->paginate(10);


        return view('admin.users.list', compact('users', 'page_heading'));
    }


    // Get all users
    public function customers(Request $request)
    {

        $page_heading = "Customers";
        $users = User::where('user_type_id', 2)->paginate(10);

        return view('admin.users.list', compact('users', 'page_heading'));
    }
}
