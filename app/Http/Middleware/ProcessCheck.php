<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class ProcessCheck
{
    public function handle($request, Closure $next)
    {
        if(!empty($request->input('bearer'))) {
            $http = new \GuzzleHttp\Client();
            $resp = $http->request('post', $this->apiUrl('/api/login-check'), [
                'headers' => ['Authorization' => 'Bearer '.$request->input('bearer')],
            ]);

            $user = json_decode($resp->getBody(), true);
            if(empty($user['udx'])) {
                abort(401);
            }

            $mUser = User::find($user['udx']);
            if(!$mUser) {
                abort(401);
            }

            Auth::login($mUser);
        }

        $action = $request->route()->getAction();
        if(empty($action['controller'])) {
            return $next($request);
        }

        $tempPath = explode('\\', $action['controller']);
        $tempPath = explode('@', end($tempPath));

        if(!accessCheck($request->input('mode', 'central'), $tempPath[0], $tempPath[1])) {
            abort(Auth::user() ? 405 : 401);
        }

        return $next($request);
    }

    private function apiUrl($path)
    {
        return rtrim(config('services.eventspack.api_base_url'), '/').$path;
    }
}
