<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLogin(){
        return view('admin.auth.login');
    }


    public function login(LoginRequest $request){
        if(auth()->guard('admin')->attempt(['username'=>$request->input('username'), 'password'=>$request->input('password')]))
        {
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('admin.showlogin');
        }
    }


    public function logout(){
        
        auth()->logout();

        return redirect()->route('admin.showlogin');
    }

    // public function make_new_admin(){
    //     $admin = App\Models\Admin();
    //     $admin->name     = "admin"; 
    //     $admin->email    = "admin10@gmail.com"; 
    //     $admin->username = 'admin';
    //     $admin->password = bcrypt("admin"); 
    //     $admin->com_code = 1 ; 
    //     $admin->save(); 

    // }
}

