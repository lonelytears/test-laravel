<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Look;
use App\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * 首页
     * @return [type] [description]
     */
    public function index()
    {
        $user = User::getLoginUser();
        $auth = Auth::getLoginAuth();
        $looks = [];

        // 如果用户获取成功则取到相关看单
        if ($user) {
            $looks = $user->looks()->orderBy('look_at', 'desc')->orderBy('created_at', 'desc')->get();
        }

        return view('home/index', compact('user', 'looks', 'auth'));
    }
}
