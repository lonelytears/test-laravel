<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Rules\Mobile;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Encryption\DecryptException;
use App\User;
use App\Look;
use App\LookHouse;
use App\Jobs\PushWork;
use Carbon\Carbon;

class LookController extends Controller
{
    /**
     * 管家确认页
     * @return [type] [description]
     */
    public function confirm($code)
    {
        try {
            // 解密
            $text = decrypt($code);

            /**
             * 按 / 分割, $param
             * [0] => code
             * [1] => validate_user_id
             * [2] => validate_user_mobile
             */
            $param = explode('/', $text);

            // 查找该看单
            $look = Look::where(['code' => $param[0]])->first();
            $look_houses = $look->look_houses()->get();

            if ($look) {

                $user = $look->user()->get()->first();

                Log::info('Confirm Look Scan', [
                    'code' => $param[0],
                    'validate_user_id' => $param[1],
                    'validate_user_mobile' => $param[2],
                    'look' => $look->toArray(),
                    'user' => $user->toArray()
                ]);
            } else {

                Log::info('Confirm Look Scan Errors', [
                    'code' => $param[0],
                    'validate_user_id' => $param[1],
                    'validate_user_mobile' => $param[2]
                ]);

                return abort(403);
            }

            return view('look/confirm', compact('look', 'look_houses', 'user', 'param'));
        } catch (DecryptException $e) {

            return abort(403, $e);
        }
    }

    /**
     * 管家确认接口
     * @return [type] [description]
     */
    public function complete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'validate_user_id' => 'required',
            'validate_user_mobile' => ['required', new Mobile]
        ]);

        $look = Look::find(request()->id);

        $validator->after(function ($validator) use ($look) {
            if ($look->status !== 0) {
                $validator->errors()->add('status', '已失效或已确认');
            }
            if (!$look->look_at->isToday()) {
                $validator->errors()->add('look_at', '该看单已过期');
            }
        });

        // 判断校验是否通过
        if ($validator->fails()) {

            // 捕获所有的错误
            $errors = $validator->errors();

            Log::error('Look Complete Errors', [
                'look' => $look->toArray(),
                'request' => $request->all(),
                'errors' => $errors
            ]);

            // 返回第一条错误信息
            return response()->json([
                'code' => 1,
                'error' => $errors->first()
            ]);
        } else {

            // 更新
            $look->update([
                'validate_user_id' => $request->validate_user_id,
                'validate_user_mobile' => $request->validate_user_mobile,
                'validate_at' => now(),
                'status' => 1,
            ]);

            Log::info('Look Complete', [
                'look' => $look->toArray(),
                'request' => $request->all()
            ]);

            // 添加到发送队列
            $this->dispatch(new PushWork($look));

            return response()->json([
                'code' => 0,
                'result' => '审核通过'
            ]);
        }
    }

    /**
     * 看单详情
     * @return [type] [description]
     */
    public function detail(Look $look)
    {
        $user = $look->user()->get()->first();
        $look_houses = $look->look_houses()->get();

        return view('look/detail', compact('look', 'look_houses', 'user'));
    }

    /**
     * 提交看房单页面
     * @return [type] [description]
     */
    public function submit()
    {
        return view('look/submit');
    }

    /**
     * 提交看单处理
     * @return [type] [description]
     */
    public function create(Request $request)
    {
        // 校验规则
        $this->validate(request(), [
            'city_id' => 'required|numeric|min:2',
            'city_name' => 'required|string|min:2',
            'community_code' => 'required|numeric|min:2',
            'community_name' => 'required|string|min:2',
            'look_at' => 'required|date|after_or_equal:now',
            'house_list' => 'required|string|min:2'
        ]);

        // 解析房源
        $look_houses = json_decode(urldecode($request->house_list), true);

        // 组装看单参数
        $param = array_merge(request([
            'city_id',
            'city_name',
            'community_code',
            'community_name'
        ]), [
            'look_at' => Carbon::parse($request->look_at),
            'code' => self::build_look_code(request(['city_name'])),
            'user_id' => User::getLoginUser()->id
        ]);

        // 保存看单
        $look = Look::create($param);

        // 保存看单房源
        $look->look_houses()->createMany($look_houses);

        return redirect()->route('home')->with('messages', '约看创建成功');
    }

    /**
     * 生成看单编码 eg: 东莞, 28 => DG-28-XXXXXXXXX
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    private static function build_look_code($param)
    {
        $sort_name = pinyin_abbr($param['city_name']);

        return strtoupper($sort_name . '-' . uniqid());
    }
}
