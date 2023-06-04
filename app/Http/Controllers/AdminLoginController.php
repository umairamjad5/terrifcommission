<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminLogin;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Hash;
use Session;

class AdminLoginController extends Controller
{
    function index()
    {
        return view('index');
    }

    function getdata(Request $request)
    {
        $request->validate([
            'email' =>  'required|email',
            'password'  =>  'required'
        ]);

        // echo $password = Hash::make($request['password']);
        // exit;
        $admin = AdminLogin::where('email' , '=' , $request->email)->first();
        if($admin)
        {
             if (Hash::check($request->password, $admin['password']))
            {
                Session(['admin_id' => $admin->id, 'admin_role_id' => $admin->role, 'admin_name' => $admin->name]);
                return redirect('/dashboard');
            }
            else
            {
                return back()->with('error',"Password is not match with database");
            }
        }
        else
        {
            return back()->with('error',"Email is not registered");
        }
    }

    function logout()
    {
        if(Session::get('admin_id'))
        {
            Session::pull('admin_id');
            return redirect('/');
        }
    }
}
