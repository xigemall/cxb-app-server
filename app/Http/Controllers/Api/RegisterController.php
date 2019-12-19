<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{

    public function register(Request $request)
    {
        $validator = $this->validateFormRequest($request);

        // 表单验证失败
        if ($validator->fails()) {
            return response()->data($validator->errors()->toArray(), 422, '表单验证失败');
        }

        $data = $request->only(['email', 'password']);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        event(new Registered($user));
        $token = $user->createToken('register')->accessToken;
        $token = 'Bearer '.$token;
        return response()->data(['token' => $token]);
    }

    /**
     * 表单验证
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateFormRequest($request)
    {
        $rules = [
            'email' => [
                'required',
                'string',
                Rule::unique('users', 'email'),
                'email',
                'max:50',
            ],
            'password' => [
                'required',
                'string',
                'max:200',
            ]
        ];
        $attributes = [
            'email' => '邮件',
            'password' => '密码',
        ];
        $validator = Validator::make($request->input(), $rules, [], $attributes);
        return $validator;
    }
}
