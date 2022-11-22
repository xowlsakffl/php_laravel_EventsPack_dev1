<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OauthClientsController extends Controller
{      
    //외부에서 앱 생성을 요청함
    public function createAppRequest(Request $request)
    {        
        // return $request->all();
        // return $request->post('token');

        //다시 내부의 점검을 실행
        $http = new \GuzzleHttp\Client();
        $response = $http->request('post', 'https://apisdev1.eventspack.kr/oauth/clients', 
        [
            'headers' => [
                'Authorization' => 'Bearer '.$request->post('token'),
            ],
            'form_params' => [
                'name' => $request->post('name'),
                'redirect' => $request->post('redirect'),
                'bearer' => $request->post('token'),
            ]
        ]);
        return $response;
    }

    //생성된 앱 리스트
    public function listAppRequest(Request $request)
    {
        // return json_encode($request->all());
        //다시 내부의 점검을 실행
        $http = new \GuzzleHttp\Client();
        $response = $http->request('get', 'https://apisdev1.eventspack.kr/oauth/clients', 
        [
            'headers' => [
                'Authorization' => 'Bearer '.$request->post('token'),
            ],
            'query' => [
                'bearer' => $request->post('token'),
            ]
        ]);
        return $response;
    }

    //앱 사용자 등록
    public function postUserRequest(Request $request)
    {
        //변수할당
        $client_id = $request->post('client_id');
        $callback_url = $request->post('callback');
        $bearer = $request->post('bearer');

        //로그인 점검
        $http = new \GuzzleHttp\Client();
        $response = $http->request('post', 'https://apisdev1.eventspack.kr/api/login-check', 
        [
            'headers' => [
                'Authorization' => 'Bearer '.$bearer,
            ]
        ]);
    }    

    //앱 사용자 등록
    public function getUserRequest(Request $request)
    {
        //변수할당
        $client_id = $request->get('client_id');
        $callback_url = $request->get('callback');
        $bearer = $request->get('bearer');
        $state = $request->get('state');

        //로그인 점검
        $http = new \GuzzleHttp\Client();
        $response = $http->request('post', 'https://apisdev1.eventspack.kr/api/login-check', 
        [
            'headers' => [
                'Authorization' => 'Bearer '.$bearer,
            ]
        ]);

        //세션 등록
        $user = json_decode($response->getBody(), true);
        $mUser = User::find($user['udx']);
        Auth::login($mUser);

        $query = http_build_query([
            'client_id' => $client_id,
            'redirect_uri' => $callback_url,
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
            'user_id' => $user['uid'],
            'bearer' => $bearer,
        ]);
        
        return redirect('https://apisdev1.eventspack.kr/oauth/authorize?'.$query);
    }

    //앱 상에서 토큰확인
    public function appLoginCheckRequest(Request $request)
    {
        // return json_encode($request->all());
        //다시 내부의 점검을 실행
        $http = new \GuzzleHttp\Client();
        $response = $http->request('post', 'https://apisdev1.eventspack.kr/api/app-login-check', 
        [
            'headers' => [
                'Authorization' => 'Bearer '.$request->post('token'),
            ]
        ]);
        return $response;
    }

    //앱 상에서 
    public function appLoginCheck(Request $request)
    {
        $user = Auth::user();
        if(!empty($user))
        {
            return $user;
        }else{
            return abort(401);
        }
    }
}