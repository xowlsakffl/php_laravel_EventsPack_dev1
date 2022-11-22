<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Auth\Session;
// Session::put('user', $user);

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

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    //로그인 요청
    public function login(Request $request)
    {
        if(Auth::attempt(['uid' => $request->input('uid'), 'password' => $request->input('password')]))
        {
            $user = Auth::user();
            $accessToken = $user->createToken('authToken')->accessToken;
            return [ 'user' => $user, 'access_token' => $accessToken];
        }else{
            return ['alert' => ['danger', getMSG('user.notexist')]];
        }
    }
}
