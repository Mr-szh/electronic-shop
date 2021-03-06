<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Str;
use Illuminate\Contracts\Cookie\QueueingFactory as CookieJar;

class AfterLogin
{
    protected $cookies;

    public function __construct(CookieJar $cookies)
    {
        $this->cookies = $cookies;
    }

    public function handle($request, Closure $next)
    {
        // dd($request);
        $response = $next($request);

        if ($request->getMethod() != 'POST') {
            return $response;
        }

        if (!Str::endsWith($request->getRequestUri(), 'login')) {
            return $response;
        }

        $rememberTokenName = Auth::getRecallerName();
        $cookie = $this->cookies->queued($rememberTokenName);

        if (is_null($cookie)) {
            return $response;
        }

        $cookieValue = $cookie->getValue();

        // 设置cookie期限为一星期
        $this->cookies->queue($rememberTokenName, $cookieValue, 10080);

        return $response;
    }
}