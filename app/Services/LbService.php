<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * 乐邦服务
 */
class LbService
{
    /**
     * LB 签名
     * @return [type] [description]
     */
    public static function buildSign($code)
    {
        $sign = substr(hash_hmac('sha1', env('LB_BATCH') . $code, env('LB_SK'), false), 0, 5);

        Log::info('Lb buildSign', [
            'batch' => env('LB_BATCH'),
            'sk' => env('LB_SK'),
            'code' => $code,
            'sign' => $sign
        ]);

        return $sign;
    }
}