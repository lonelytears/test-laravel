<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Look;
use App\Services\MtyService;
use Illuminate\Support\Facades\Log;

class PushWork implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $look;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Look $look)
    {
        $this->look = $look;

        Log::info('助这儿看单进入推送队列', $look->toArray());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $look = $this->look;
        $look_houses = $look->look_houses()->get();
        $user = $look->user()->get()->first();

        // 备注
        $content = '带看' . $look->community_name;

        // 拼接备注
        foreach ($look_houses as $look_house) {
            $content .= $look_house->building_name . $look_house->unit . '单元' . $look_house->house_name . '号、';
        }

        // 删除多余的 、
        $content = trim($content, '、');

        // 组装参数
        $param = [
            'project_code' => $look->community_code,
            'house_code' => $look_houses->first()->house_code,
            'mobile' => $user->mobile,
            'contact' => $user->real_name,
            'content' => $content
        ];

        Log::info('助这儿看单开始推送', [
            'look' => $look->toArray(),
            'look_houses' => $look_houses->toArray(),
            'user' => $user->toArray(),
            'param' => $param
        ]);

        $mty = new MtyService();

        $response = $mty->request($param, '/api/task/create');

        Log::info('助这儿看单推送结束', [
            'look' => $look->toArray(),
            'look_houses' => $look_houses->toArray(),
            'user' => $user->toArray(),
            'param' => $param,
            'response' => json_decode($response, true)
        ]);
    }
}
