<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Services\PulinService;
use App\User;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mobile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mobile = $this->mobile;
        $pulin = new PulinService();

        // 判断是否为开发环境，如果为开发环境则使用日志记录验证码，不实际发送
        if (env('APP_ENV') == 'local') {
            Log::debug('短信模拟发送成功', User::validatecodeSend($mobile, env('PULIN_API_ALLOW_SMS_TIME', 1)));
        } else {
            $pulin->request(User::validatecodeSend($mobile, env('PULIN_API_ALLOW_SMS_TIME', 1)),'/sms/send');
        }
    }
}
