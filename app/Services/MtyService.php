<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * 猫头鹰服务
 */
class MtyService
{
    /**
     * 请求接口
     * @return [type] [description]
     */
    public function request($data, $url, $method='POST')
    {
        $url = env('MTY_URL') . $url;

        $client = new Client();

        $response = $client->request($method, $url, [
            'http_errors' => false,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => $data
        ]);

        Log::info('MTY API Request', [
            'url' => $url,
            'data' => $data,
            'method' => $method,
            'code' => $response->getStatusCode(),
            'body' => json_decode($response->getBody(), true)
        ]);

        return $response->getBody();
    }
}