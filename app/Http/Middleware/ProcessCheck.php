<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;

class ProcessCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //로그인 부터 시키기
        if(!empty($request->input('bearer')))
        {
            $http = new \GuzzleHttp\Client();
            $resp = $http->request('post', 'https://apisdev1.eventspack.kr/api/login-check', 
            [
                'headers' => ['Authorization' => 'Bearer '.$request->input('bearer'),]
            ]);
            $user = json_decode($resp->getBody(), true);
            $mUser = User::find($user['udx']);
            Auth::login($mUser);
        }

        //리퀘스트 컨트롤러/액션 해석
        $tempPath = explode('\\', $request->route()->getAction()['controller']);
        $tempPath = end($tempPath);
        $tempPath = explode('@', $tempPath);

        //접속자 권한 점검
        if(!accessCheck($request->input('mode', 'central'), $tempPath[0], $tempPath[1]))
        {
            if(Auth::User())
            {
                abort(405);
            }else{
                abort(401);
            }
        }

        // //프로젝트번호, 웹사이트번호, 메뉴번호 설정
        // $pdx = $request->input('pdx', 0);
        // echo 'pdx '.$pdx.'<br>';
        // $sdx = $request->input('sdx', 0);
        // echo 'sdx '.$sdx.'<br>';
        // $mdx = $request->input('mdx', 0);
        // echo 'mdx '.$mdx.'<br>';

        // //리소스 상태

        //접속자 권한

        //쿼리 해석
        // echo $request->path();
        // echo '<br>';
        // echo $request->url();
        // echo '<br>';
        // echo $request->fullUrl();
        // echo '<br>';
        // echo $request->method();
        // echo '<br>';
        // print_r($request->all());
        // echo '<br>';
        // echo '<pre>';
        // echo print_r($request->route()->getAction());
        // echo '</pre>';
        // echo '<br>';
        // print_r($request->all());
        // var_dump($request->all());
        // $action = Route::currentRouteAction();

        return $next($request);
    }
}
