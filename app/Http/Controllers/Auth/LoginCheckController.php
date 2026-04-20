<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginCheckController extends Controller
{
    public function loginCheckRequest(Request $request)
    {
        if(empty($request->input('bearer'))) {
            abort(400);
        }

        $http = new \GuzzleHttp\Client();
        $response = $http->request('post', $this->apiUrl('/api/login-check'), [
            'headers' => [
                'Authorization' => 'Bearer '.$request->input('bearer'),
            ],
        ]);

        return $this->jsonProxyResponse($response);
    }

    public function loginCheck(Request $request)
    {
        if(Auth::user()) {
            return Auth::user();
        }

        abort(401);
    }

    public function logoutRequest(Request $request)
    {
        if(empty($request->input('bearer'))) {
            abort(400);
        }

        $http = new \GuzzleHttp\Client();
        $response = $http->request('post', $this->apiUrl('/api/logout-check'), [
            'headers' => [
                'Authorization' => 'Bearer '.$request->input('bearer'),
            ],
        ]);

        return response('', $response->getStatusCode());
    }

    public function logoutCheck(Request $request)
    {
        Auth::user()->token()->revoke();
        return response('', 204);
    }

    private function apiUrl($path)
    {
        return rtrim(config('services.eventspack.api_base_url'), '/').$path;
    }

    private function jsonProxyResponse($response)
    {
        return response($response->getBody()->getContents(), $response->getStatusCode())
            ->header('Content-Type', 'application/json');
    }
}
