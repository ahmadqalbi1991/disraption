<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CountryModel;
use Illuminate\Http\Request;
use Validator;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!get_user_permission('masters_country', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Countries";

        $countries = CountryModel::where(['deleted' => 0])
        ->orderByRaw('COALESCE(updated_at, created_at) DESC')
        ->get();





        return view('admin.country.list', compact('page_heading', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!get_user_permission('masters_country', 'c')) {
            return redirect()->route('admin.restricted_page');
        }


        $page_heading = "Create Country";
        $id = "";
        $prefix = "";
        $name = "";
        $dial_code = "";
        $image = "";
        $active = "1";
        return view("admin.country.create", compact('page_heading', 'id', 'name', 'dial_code', 'active', 'prefix'));
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
            $check_exist = CountryModel::where(['deleted' => 0, 'name' => $request->name])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'name' => $request->name,
                    'prefix' => strtoupper($request->prefix),
                    'dial_code' => $request->dial_code,
                    'active' => $request->active,
                ];

                if ($request->id != "") {
                    $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                    $country = CountryModel::find($request->id);
                    $country->update($ins);
                    $status = "1";
                    $message = "Country updated succesfully";
                } else {
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    CountryModel::create($ins);
                    $status = "1";
                    $message = "Country added successfully";
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
        if (!get_user_permission('masters_country', 'u')) {
            return redirect()->route('admin.restricted_page');
        }


        $country = CountryModel::find($id);
        if ($country) {
            $page_heading = "Edit Country";
            $id = $country->id;
            $name = $country->name;
            $prefix = $country->prefix;
            $dial_code = $country->dial_code;
            $active = $country->active;
            return view("admin.country.create", compact('page_heading', 'id', 'name', 'dial_code', 'active', 'prefix'));
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
    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $country = CountryModel::find($id);
        if ($country) {
            $country->deleted = 1;
            $country->active = 0;
            $country->save();
            $status = "1";
            $message = "Country removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }

    public function apiGetCountries()
    {

        // Get countries list sort by name
        $countries = CountryModel::select(["id", "name", "prefix", "dial_code"])->where(['deleted' => 0, 'active' => 1])->orderBy('name', 'asc')->paginate(1000);
        if ($countries) {

            $countries = convert_all_elements_to_string($countries->toArray());
            $countries = cleanPaginationResultArray($countries);

            return response()->success('Countries fetched successfully', $countries);
        } else {
            return response()->error('No countries found');
        }
    }
}
