<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Models\RolePermissions;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login()
    {
        //send_email_test("soorajsabu117@gmail.com","hi","test");
        if (Auth::check() && (Auth::user()->role == '1')) {
            return redirect()->route('admin.dashboard');
        }
        // echo Hash::make('Hello@1985');
        return view('admin.login');
    }


    public function check_login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        // Validate request
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            if (Auth::check() && (Auth::user()->role == '1') && Auth::user()->active == '1') {


                $request->session()->put('user_id', Auth::user()->id);
                if ($request->timezone) {
                    $request->session()->put('user_timezone', $request->timezone);
                }
                
                try {
                    $permission = RolePermissions::where(['user_role_id_fk' => Auth::user()->role_id])->get();

                    if ($permission && $permission->count() > 0) {
                        $permission = $permission->toArray();
                        $user_permissions = array_column($permission, 'permissions', 'module_key');
                        $request->session()->put('user_permissions', $user_permissions);
                    } else {
                        $request->session()->put('user_permissions', []);
                    }
                } catch (\Throwable $th) {
                    info('Error in getting permissions:: ');
                    info($th->getMessage());
                }
                $user_ne = User::find(Auth::user()->id);
                $user_ne->last_login = gmdate('Y-m-d H:i:s');
                $user_ne->save();

                return response()->json(['success' => true, 'message' => "Logged in successfully."]);
            } else {

                if (Auth::user()->active == '0') {

                    Auth::logout();
                    return response()->json(['success' => false, 'message' => "Your account is disabled by the admin!"]);

                }
               

                return response()->json(['success' => false, 'message' => "Invalid Credentials!"]);
            }

        }

        return response()->json(['success' => false, 'message' => "Invalid Credentials!"]);
    }
    public function logout(){
        session()->pull("user_id");
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
