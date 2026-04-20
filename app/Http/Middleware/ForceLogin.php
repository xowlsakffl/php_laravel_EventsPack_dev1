<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class ForceLogin
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

        return $next($request);
    }

    private function apiUrl($path)
    {
        return rtrim(config('services.eventspack.api_base_url'), '/').$path;
    }
}
