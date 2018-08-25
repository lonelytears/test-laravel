<?php

namespace App\Http\Controllers;

use Validator;
use App\Rules\Mobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\PulinService;
use App\User;
use App\Jobs\SendSms;

class PulinApiController extends Controller
{
    /**
     * 获取城市接口
     * @return [type] [description]
     */
    public function getCity()
    {
        $pulin = new PulinService();
        return response($pulin->request([
            'is_all_display' => 1
        ], '/city'), 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * 获取小区列表
     * @return [type] [description]
     */
    public function getCommunity()
    {
        $pulin = new PulinService();
        return response($pulin->request(array_merge(request([
            'city_id',
            'community_name'
        ]), [
            'page' => 0,
            'pagesize' => 0
        ]), '/community/search_community_war'), 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * 获取小区详情
     * @return [type] [description]
     */
    public function getCommunityInfo()
    {
        $pulin = new PulinService();

        $param = join('/', array_merge(request([
            'city_id',
            'community_code'
        ]), [
            'flag' => 'war_code',
            'page' => 0,
            'pagesize' => 0
        ]));

        $url = '/community/get_info/' . $param;

        return response($pulin->request([], $url, 'GET'), 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * 获取座栋列表
     * @return [type] [description]
     */
    public function getBuilding()
    {
        $pulin = new PulinService();
        return response($pulin->request(array_merge(request([
            'city_id',
            'community_code',
            'building_name'
        ]), [
            'page' => 0,
            'pagesize' => 0
        ]), '/build/search_build_war'), 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * 获取房间列表
     * @return [type] [description]
     */
    public function getHouse()
    {
        $pulin = new PulinService();
        return response($pulin->request(array_merge(request([
            'city_id',
            'community_code',
            'building_code',
            'house_name'
        ]), [
            'page' => 0,
            'pagesize' => 0
        ]), '/house/search_house_war'), 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * 发送短信接口
     * @return [type] [description]
     */
    public function sendValidateCode(Request $request)
    {
        // 构造验证规则
        $validator = Validator::make($request->all(), [
            'mobile' => [
                'required',
                new Mobile
            ]
        ]);

        $validator->after(function ($validator) use ($request) {
            if (User::validatecodeCheck($request->mobile)) {
                $validator->errors()->add('mobile:' . $request->mobile, '该手机号重复发送');
            }
        });

        if ($validator->fails()) {
            // 捕获所有的错误
            $errors = $validator->errors();

            Log::error('Pulin API Errors', [
                'errors' => $errors
            ]);

            // 返回第一条错误信息
            return response()->json([
                'code' => 1,
                'error' => $errors->first()
            ]);
        } else {

            // 添加到发送队列
            $this->dispatch(new SendSms($request->mobile));

            return response()->json([
                'code' => 0,
                'result' => '短信已进入发送队列，注意查收'
            ]);
        }
    }
}
