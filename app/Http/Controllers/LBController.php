<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Rules\Mobile;
use Endroid\QrCode\QrCode;
use App\Services\LbService;

class LBController extends Controller
{
    /**
     * LB 二维码回调
     * @return function [description]
     */
    public function callback(Request $request)
    {

        Log::info('LB Callback', [
            'data' => $request->all()
        ]);

        // 构造验证规则
        $validator = Validator::make($request->all(), [
            'staff_id' => 'required',
            'code' => 'required',
            'staff_mobile' => [
                'required',
                new Mobile
            ]
        ]);

        // 获取返回的 code，使用 _ 分割为数组
        $data = explode('_', $request->code);

        // Hook 其他验证
        $validator->after(function ($validator) use ($data) {
            // 校验数据有效性
            if (LbService::buildSign($data[1]) !== $data[2]) {
                $validator->errors()->add('sign', '数据签名失败');
            }
        });

        // 判断校验是否通过
        if ($validator->fails()) {

            // 捕获所有的错误
            $errors = $validator->errors();

            Log::error('LB Errors', [
                'errors' => $errors
            ]);

            // 返回第一条错误信息
            return response()->json([
                'code' => 1,
                'error' => $errors->first()
            ]);
        } else {
            // 拼接参数
            $param = $data[1] .'/'. $request->staff_id .'/'. $request->staff_mobile;
            // 加密
            $code = encrypt($param);

            // 拼接 URL 地址
            $url = env('APP_URL') .'/look/confirm/'. $code;

            Log::info('LB Success', [
                'data' => $request->all(),
                'param' => $param,
                'code' => $code,
                'url' => $url
            ]);

            return response()->json([
                'code' => 0,
                'result' => [
                    'url' => $url
                ]
            ]);
        }
    }

    /**
     * 生成 LB 二维码
     * @param  [type] $code [description]
     * @return [type]       [description]
     */
    public function qrcode($code)
    {
        $url = env('LB_URL') .'/'. env('LB_BATCH') .'_'. $code .'_'. LbService::buildSign($code);
        $qrCode = new QrCode($url);
        return response($qrCode->writeString(), 200, [
            'Content-Type' => $qrCode->getContentType()
        ]);
    }
}
