<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginCheckController extends Controller
{      
    //외부에서 로그인 상태 점검을 요청받음
    public function loginCheckRequest(Request $request)
    {
        // return json_encode($request->all());
        // //기존 토큰 확인
        if(empty($request->input('bearer')))
        {
            abort(500);
        }
        //다시 내부의 점검을 실행
        $http = new \GuzzleHttp\Client();
        $response = $http->request('post', 'https://apisdev1.eventspack.kr/api/login-check', 
        [
            'headers' => [
                'Authorization' => 'Bearer '.$request->input('bearer'),
            ]
        ]);
        return $response;
    }
    
    //로그인 상태 확인
    public function loginCheck(Request $request)
    {
        if(!empty(Auth::user()))
        {
            return Auth::user();
        }else{
            return abort(401);
        }
    }
    
    //외부에서 로그아웃을 요청받음
    public function logoutRequest(Request $request)
    {
        // return json_encode($request->all());
        //다시 내부의 점검을 실행
        $http = new \GuzzleHttp\Client();
        $response = $http->request('bearer', 'https://apisdev1.eventspack.kr/api/logout-check', 
        [
            'headers' => [
                'Authorization' => 'Bearer '.$request->input('bearer'),
            ]
        ]);
        return;
        // return $response;
    }

    //로그아웃
    public function logoutCheck(Request $request)
    {
        // return json_encode($request->headers->all());
        $user = Auth::user()->token()->revoke();
        return;
    }
}