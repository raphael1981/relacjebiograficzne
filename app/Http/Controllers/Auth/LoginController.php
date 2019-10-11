<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    public function showLoginForm(){


        $title = 'Logowanie';

        return view('admin.login.master',[
            'title'=>$title,
            'controller'=>'admin/login/login.controller.js'
        ]);

    }


    public function login(Request $request){

        $data = $request->only('email','password');
        $data['status'] = 1;

        if(Auth::attempt($data, $request->get('remember'))){

            return response(1, 200);

        }else{

            return response(2, 200);

        }

    }

    public function logout(){

        Auth::guard()->logout();

        return redirect('/');

    }



}
