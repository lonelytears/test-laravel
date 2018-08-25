<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * 朴邻开放平台服务
 */
class PulinService
{
    /**
     * 接口请求方法
     * @param  [type] $data [description]
     * @param  [type] $url  [description]
     * @return [type]       [description]
     */
    public function request($data, $url, $method='POST')
    {
        $url = env('PULIN_API_URL') . $url;

        $client = new Client();

        $response = $client->request($method, $url, [
            'http_errors' => false,
            'headers' => self::buildHeader($data),
            'form_params' => $data
        ]);

        Log::info('Pulin API Request', [
            'url' => $url,
            'header' => self::buildHeader($data),
            'data' => $data,
            'method' => $method,
            'code' => $response->getStatusCode(),
            'body' => json_decode($response->getBody(), true)
        ]);

        return $response->getBody();
    }

    /**
     * 生成 Header 头
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function buildHeader($data)
    {
        $app_key = env('PULIN_API_KEY', false);
        $app_secret = env('PULIN_API_SECRET', false);
        $app_time = time();

        if ($app_secret && $app_key) {
            $header = [
                'APPKEY' => $app_key,
                'TIMESTAMP' => $app_time,
                'SIGN' => self::buildSign($data, $app_secret, $app_time)
            ];

            Log::info('Pulin API Building Header', [
                'data' => $data,
                'header' => $header
            ]);

            return $header;
        }

        return [];
    }

    /**
     * 开放平台签名规则
     * @return [type] [description]
     */
    public static function buildSign($data, $app_secret = '', $app_time=0)
    {
        ksort($data);
        $build_str = '';
        foreach ($data as $k => $v) {
            if ($v === '') {
                continue;
            }
            if ($build_str !== '') {
                $build_str .= '&';
            }
            if ('UTF-8' === mb_detect_encoding($v)) {
                $v = rawurlencode($v);
            }
            $build_str .= "{$k}={$v}";
        }
        $build_str .= '&app_secret=' . $app_secret;
        $build_str .= '&app_time=' . $app_time;
        $sign = md5($build_str);
        $sign = strtoupper($sign);

        Log::info('Pulin API Building Sign', [
            'data' => $data,
            'app_secret' => $app_secret,
            'app_time' => $app_time,
            'build_str' => $build_str,
            'sign' => $sign
        ]);

        return $sign;
    }
}