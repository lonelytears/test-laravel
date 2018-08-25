<?php

namespace App\Http\Middleware;

use Closure;
use App\Auth;
use App\User;

class CheckUser
{
    /**
     * 检查用户的手机号
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 校验是否绑定手机号
        if (!Auth::hasBindingMobile()) {
            return redirect()->route('bindingMobile', ['auth' => Auth::getLoginAuth()->id]);
        }

        // 校验用户是否创建
        if (!User::getLoginUser()) {
            return redirect()->route('createuser', ['auth' => Auth::getLoginAuth()->id]);
        }

        return $next($request);
    }
}
