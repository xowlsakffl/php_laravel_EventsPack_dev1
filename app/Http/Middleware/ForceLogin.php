<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;

class ForceLogin
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */

    public function handle($request, Closure $next)
    {        
        // print_r($request, true);
        // var_dump(['bearer' => $request->input('bearer')]);
        // exit;
        //로그인 점검
        if(!empty($request->input('bearer')))
        {
            $http = new \GuzzleHttp\Client();
            $resp = $http->request('post', 'https://apisdev1.eventspack.kr/api/login-check', 
            [
                'headers' => ['Authorization' => 'Bearer '.$request->input('bearer'),]
            ]);
            // var_dump($resp->getBody());
            $user = json_decode($resp->getBody(), true);
            $mUser = User::find($user['udx']);
            Auth::login($mUser);
            // print_r($mUser);exit;
        }
       
        // $response = $next($request);
        // $IlluminateResponse = 'Illuminate\Http\Response';
        // $SymfonyResopnse = 'Symfony\Component\HttpFoundation\Response';
        // $headers = [
        //     // 'Access-Control-Allow-Origin' => '*',
        //     // 'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, PATCH, DELETE',
        //     // 'Access-Control-Allow-Headers' => 'Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Authorization , Access-Control-Request-Headers',
        // ];
        
        // if($response instanceof $IlluminateResponse)
        //  {
        //     foreach ($headers as $key => $value) {
        //         $response->header($key, $value);
        //     }
        //     return $response;
        // }

        // if($response instanceof $SymfonyResopnse) {
        //     foreach ($headers as $key => $value) {
        //         $response->headers->set($key, $value);
        //     }
        //     return $response;
        // }

        return $next($request);
    }
}
