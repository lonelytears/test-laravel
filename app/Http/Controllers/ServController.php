<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Log;

class ServController extends Controller
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
     * 微信验证服务器
     * @return [type] [description]
     */
    public function verification()
    {
        $app = Factory::officialAccount($this->wx_config);

        $response = $app->server->serve();

        return $response;
    }

    /**
     * Webhook 更新
     * @return [type] [description]
     */
    public function webhook()
    {
        // 仅对开发环境配置生效
        if (env('APP_ENV', false) == 'local') {
            // 获取应用所在目录
            $app_path = storage_path();
            // 获取执行用户
            $user = exec('whoami');
            // 获取开发分支
            $branch = env('WEBHOOK_ENV', 'develop');
            // 构建命令
            $cmd = 'cd ' . $app_path . '; git checkout '. $branch .'; git pull; composer update';
            // 执行命令
            $log = shell_exec($cmd);

            Log::debug('Webhook update', compact('app_path', 'user', 'branch', 'cmd', 'log'));

            return $log;
        } else {
            Log::debug('Webhook error');
        }
    }
}
