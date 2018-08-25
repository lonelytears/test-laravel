<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class Auth
{
    /**
     * 判断登录
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Session 中不存在 Open ID 则跳转到登录路由
        if (!session()->has('auth')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
