<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rule = [
            'uid' => ['required', 'max:32', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            // 'cell' => ['required', 'max:20'],
            // 'tel' => ['required', 'max:20'],
        ];

        $messages = [
            'uid.required' => '아이디를 입력해주세요.',
            'uid.max' => '최대 32자까지 가능합니다.',
            'uid.unique' => '이미 존재하는 아이디입니다.',
            'password.required' => '비밀번호를 입력해주세요.',
            'password.string' => '문자를 포함해주세요.',
            'password.min' => '4자 이상 입력해주세요.',
            'password.confirmed' => '비밀번호가 일치하지 않습니다.',
            'name.required' => '성명을 입력해주세요.',
            'name.string' => '문자만 입력해주세요.',
            'name.max' => '최대 20자까지 가능합니다.',
            'email.required' => '이메일을 입력해주세요.',
            'email.string' => '문자만 입력가능합니다.',
            'email.email' => '이메일 형식에 맞게 입력해주세요.',
            'email.max' => '최대 50자까지 가능합니다.',
            'email.unique' => '이미 존재하는 이메일입니다.',
            'cell.required' => '휴대폰번호를 입력해주세요.',
            'cell.max' => '최대 20자까지 가능합니다.',
            'tel.required' => '연락처를 입력해주세요.',
            'tel.max' => '최대 20자까지 가능합니다.',
        ];

        $validator = Validator::make($data, $rule, $messages);
        if($validator->fails())
        {
            return 'Validation Error.'.$validator->errors();
        }
        return Validator::make($data, $rule, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'uid' => $data['uid'],
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'email' => $data['email'],
            // 'cell' => $data['cell'],
            // 'tel' => $data['tel'],
        ]);
    }

    //점검 후 가입처리
    protected function register(Request $request)
    {
        // return json_encode($request->all());
        // return response()->json($request->all());

        // $this->validator($request->all())->validate();
        $this->validator($request->all());
        $user = $this->create($request->all());

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(
            [ 'user' => $user, 'access_token' => $accessToken]
        );
    }
}
