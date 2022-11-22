<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

//로그인 안되어 있을 때 리턴할 Auth:api전용 미들웨어
class ForceAuthApiRedirect
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
        // return response()->json(Auth::user(), 200);
        // if(empty(Auth::user()))
        // {
        //     return response()->json(401, 200);
        //     // return response()->json(['status' => 401, getMSG(401)], 200);
        // }
        return $next($request);
    }
}
