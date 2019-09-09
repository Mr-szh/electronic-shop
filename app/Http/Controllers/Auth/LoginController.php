<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * 成功之后的跳转路径
     *
     * @var string
     */
    protected $redirectTo = '/';

    // 最多尝试次数，防暴力破解
    protected $maxAttempts = 3;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('login.after');
        $this->middleware('guest')->except('logout');
    }

     /**
     * 重写 showLoginForm 方法
     * @url https://laravel-china.org/topics/16682?#reply73955
     * @param \App\Http\Requests\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
