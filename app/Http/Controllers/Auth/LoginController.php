<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * 成功之后的跳转路径
     */
    protected $redirectTo = '/';

    // 最多尝试次数，防暴力破解
    protected $maxAttempts = 3;

    public function __construct()
    {
        $this->middleware('login.after');
        $this->middleware('guest')->except('logout');
    }

     /**
     * 重写 showLoginForm 方法
     */
    public function showLoginForm(Request $request)
    {
        if ($request->session()->has('url.intended')) {
            if (strpos($request->session()->get('url.intended'), '/admin')) {
                $request->session()->forget('url.intended');
            }
        }
        return view('auth.login');
    }
}
