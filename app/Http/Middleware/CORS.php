<?php

namespace App\Http\Middleware;

use Closure;

class CORS
{
    public function handle($request, Closure $next)
    {
        $origin = $request->headers->get('Origin', '');
        $allowedOrigins = config('services.eventspack.allowed_origins', []);

        if(in_array($origin, $allowedOrigins, true)) {
            header('Access-Control-Allow-Origin: '.$origin);
        }

        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Requested-With, Accept, X-Token-Auth, Application, x-csrf-token');
        header('Access-Control-Expose-Headers: Authorization, Foobar');

        return $next($request);
    }
}
