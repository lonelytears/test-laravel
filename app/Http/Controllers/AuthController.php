<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Log;
use App\Auth;
use App\User;

class AuthController extends Controller
{
    // 微信配置
    protected $wx_config = [];

    /**
     * 初始化微信配置
     */
    public function __construct()
    {
        $this->wx_config = [
            'app_id' => env('WX_APP_ID'),
            'secret' => env('WX_SECRET'),
            'response_type' => env('WX_RESPONSE_TYPE'),
            'log' => [
                'level' => env('WX_LOG_LEVEL'),
                'file' => storage_path('logs/wx.log')
            ]
        ];
    }

    /**
     * 用户拉起微信授权
     * @return [type] [description]
     */
    public function login()
    {
        $config = array_merge($this->wx_config, [
            'oauth' => [
                'scopes' => ['snsapi_userinfo'],
                'callback' => env('APP_URL') . '/auth/callback'
            ]
        ]);

        $app = Factory::officialAccount($config);

        $oauth = $app->oauth;

        if (session()->has('auth')) {

            $user = Auth::find(session('auth')['id']);

            Log::info('用户缓存未过期,跳过登录', $user->toArray());

            return redirect()->route('home');
        } else {

            Log::info('用户未登录或已过期,拉起授权');

            return $oauth->redirect();
        }
    }

    /**
     * 微信授权回调
     * @return function [description]
     */
    public function callback()
    {
        $app = Factory::officialAccount($this->wx_config);

        $oauth = $app->oauth;

        $wx_user = $oauth->user();

        Log::info('用户授权成功, Open ID:', $wx_user->toArray());

        // 查找微信用户 Open Id
        $auth = Auth::where('open_id', $wx_user->getId())->first();

        // 如果用户不存在则创建用户
        if (!$auth) {

            // 创建微信用户
            Log::info('用户不存在,准备创建微信用户', $wx_user->toArray());
            $auth = Auth::create([
                'open_id' => $wx_user->getId(),
                'original' => json_encode($wx_user->getOriginal()),
                'flag' => 1  // 微信标识
            ]);

            Log::info('用户创建完成', $auth->toArray());
        }

        // 缓存微信用户信息
        session()->put('auth', $auth->toArray());

        if (session()->has('auth')) {

            Log::info('用户登录成功', $auth->toArray());

            return redirect()->route('home');
        } else {

            Log::error('登录失败');
        }
    }

    /**
     * 退出
     * @return [type] [description]
     */
    public function logout()
    {
        Log::info('用户手动注销', ['auth' => session()->get('auth')]);
        session()->forget('auth');
    }
}
