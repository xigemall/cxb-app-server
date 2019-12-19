<?php

namespace App\Http\Controllers\Api;

use App\Events\Api\Logined;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    // 客户端用户名字段
    protected $username = 'username';

    // 数据库用户名字段
    protected $loginFields = ['email', 'mobile'];

    public function __construct()
    {
//        $this->middleware('guest')->except('logout');
    }

    /**
     * 登录
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        $validator = $this->validateLogin($request);
        if ($validator->fails()) {
            return response()->data($validator->errors(), 422, '表单验证失败');
        }
        // 登录
        $login = $this->attemptLogin($request);
        if ($login) {
            $user = Auth::user();
//            event(new Logined($user));
            $token = $user->createToken('login')->accessToken;
            $token = 'Bearer '.$token;
            return response()->data(['token' => $token]);
        }
        // 登录失败
        return response()->data([], 400, '用户名或密码错误');
    }

    /**
     *  验证登录
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateLogin($request)
    {
        $rules = [
            $this->username => [
                'required',
                'string',
                'max:50'
            ],
            'password' => [
                'required',
                'string',
                'max:200',
            ]
        ];
        $attributes = [
            'username' => '用户名',
            'password' => '密码',
        ];
        return Validator::make($request->input(), $rules, [], $attributes);
    }

    /**
     * 登录
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return collect($this->loginFields)->contains(function ($value) use ($request) {
            $account = $request->get($this->username);
            $password = $request->get('password');
            return Auth::guard()->attempt([$value => $account, 'password' => $password]);
        });
    }

    /**
     *  token 过期
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized()
    {
        return response()->json('Unauthorized', 401);
    }

    /**
     * 退出
     * @return mixed
     */
    public function logout()
    {
        if (Auth::guard('api')->check()) {
            $logout = Auth::guard('api')->user()->token()->delete();
            if ($logout) {
                return response()->data();
            }
            return response()->data([], 400, '退出失败');
        }
        return response()->data([], 400, '未登录，退出失败');
    }
}
