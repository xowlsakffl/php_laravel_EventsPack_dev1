<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OauthClientsController extends Controller
{
    public function createAppRequest(Request $request)
    {
        $http = new \GuzzleHttp\Client();
        $response = $http->request('post', $this->apiUrl('/oauth/clients'), [
            'headers' => [
                'Authorization' => 'Bearer '.$request->post('token'),
            ],
            'form_params' => [
                'name' => $request->post('name'),
                'redirect' => $request->post('redirect'),
                'bearer' => $request->post('token'),
            ],
        ]);

        return $this->jsonProxyResponse($response);
    }

    public function listAppRequest(Request $request)
    {
        $http = new \GuzzleHttp\Client();
        $response = $http->request('get', $this->apiUrl('/oauth/clients'), [
            'headers' => [
                'Authorization' => 'Bearer '.$request->post('token'),
            ],
            'query' => [
                'bearer' => $request->post('token'),
            ],
        ]);

        return $this->jsonProxyResponse($response);
    }

    public function postUserRequest(Request $request)
    {
        $http = new \GuzzleHttp\Client();
        $response = $http->request('post', $this->apiUrl('/api/login-check'), [
            'headers' => [
                'Authorization' => 'Bearer '.$request->post('bearer'),
            ],
        ]);

        return $this->jsonProxyResponse($response);
    }

    public function getUserRequest(Request $request)
    {
        $clientId = $request->get('client_id');
        $callbackUrl = $request->get('callback');
        $bearer = $request->get('bearer');
        $state = $request->get('state');

        $http = new \GuzzleHttp\Client();
        $response = $http->request('post', $this->apiUrl('/api/login-check'), [
            'headers' => [
                'Authorization' => 'Bearer '.$bearer,
            ],
        ]);

        $user = json_decode($response->getBody(), true);
        if(empty($user['udx'])) {
            abort(401);
        }

        $mUser = User::find($user['udx']);
        if(!$mUser) {
            abort(401);
        }

        Auth::login($mUser);

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $callbackUrl,
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
            'user_id' => $user['uid'],
            'bearer' => $bearer,
        ]);

        return redirect($this->apiUrl('/oauth/authorize?'.$query));
    }

    public function appLoginCheckRequest(Request $request)
    {
        $http = new \GuzzleHttp\Client();
        $response = $http->request('post', $this->apiUrl('/api/app-login-check'), [
            'headers' => [
                'Authorization' => 'Bearer '.$request->post('token'),
            ],
        ]);

        return $this->jsonProxyResponse($response);
    }

    public function appLoginCheck(Request $request)
    {
        if(Auth::user()) {
            return Auth::user();
        }

        abort(401);
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
