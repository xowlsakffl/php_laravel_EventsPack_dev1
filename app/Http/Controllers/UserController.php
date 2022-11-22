<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return getUser();
        return Request::all();
        //
        // return 'fdjsakl';
        // // return Auth::User();
        // return $request->User();
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    
    public function others(Request $request)
    {
        //
    }
}
