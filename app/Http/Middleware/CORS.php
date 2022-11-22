<?php

namespace App\Http\Middleware;

use Closure;

class CORS
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
        $r_url = isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:'';
        $allow_url = 
        [
            'https://eventspack.com',
            'https://eventspack.co.kr',
            'https://try.eventspack.com',
            'https://try.eventspack.co.kr',
            'http://tryeverything.or.kr',
            'http://www.tryeverything.or.kr',
            
            'https://pre.eventspack.kr',
            'https://predev.eventspack.kr',
            'https://predev1.eventspack.kr',
            'https://predev2.eventspack.kr',
            'https://predev3.eventspack.kr',
            'https://try.eventspack.kr',
            'https://trydev.eventspack.kr',
            'https://trydev1.eventspack.kr',
            'https://trydev2.eventspack.kr',
            'https://trydev3.eventspack.kr',
        ];
        if( array_search($r_url, $allow_url) !== false )
        {
            header('Access-Control-Allow-Origin: '.$r_url);
        }
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Requested-With, Accept, X-Token-Auth, Application, x-csrf-token');
        header('Access-Control-Expose-Headers: Authorization, Foobar');
        
        // return apache_request_headers();
        // return $_SERVER['HTTP_ORIGIN'];
        // if($request->getMethod() == 'OPTIONS')
        // {            
        //     return response('OPTIONS');
        // }
        return $next($request);
    }
}
