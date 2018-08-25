<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Rules\Mobile;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Auth;
use App\User;

class UserController extends Controller
{
    /**
     * 我的信息
     * @return [type] [description]
     */
    public function index(Request $request, User $user)
    {
        // 用户授权
        if ($user->can('view', $user)) {

            return view('user/index', compact('user'));
        } else {

            Log::warning('非法访问', [
                'from' => session('user'),
                'to' => $user->toArray()
            ]);

            return abort(403);
        }
    }

    /**
     * 用户绑定手机页面
     * @return [type] [description]
     */
    public function binding(Request $request, Auth $auth)
    {
        if ($auth->can('binding', $auth)) {

            return view('user/binding', compact('auth'));
        } else {
            // 如果用户手机号存在则不需要绑定
            return redirect()->route('home');
        }
    }

    /**
     * 用户绑定手机处理逻辑
     * @return [type] [description]
     */
    public function bindingMobile(Request $request, Auth $auth)
    {
        if ($auth->can('binding', $auth)) {
            // 数据校验
            $validator = Validator::make($request->all(), [
                'mobile' => ['required', new Mobile, Rule::unique('auths')->where(function ($query) {
                    // flag: 1 微信，2 安居客
                    return $query->where('flag', 1);
                })],
                'validate_code' => 'required'
            ]);

            // 校验验证码
            $validator->after(function ($validator) use ($request) {
                if (User::validatecodeCheck($request->mobile) !== $request->validate_code) {
                    $validator->errors()->add('validate_code', '验证码错误');
                }
            });

            // 校验是否通过
            if (!$validator->fails()) {

                // 更新 auth 手机号
                $auth->update(['mobile' => $request->mobile]);

                // 更新会话
                session()->put('auth', $auth->toArray());

                // 查询用户手机是否存在
                $user = User::where('mobile', request('mobile'))->first();

                // 如果用户存则说明为重新绑定
                if ($user) {
                    // 用户已存在 重新绑定成功
                    return redirect()->route('home')->with('messages', '绑定成功');;
                } else {
                    // 用户不存在 补充用户信息
                    return redirect()->route('createuser', [$auth->id]);
                }

            } else {
                // 手机号已绑定 更换
                return back()->withInput()->withErrors($validator->errors());
            }
        } else {
            // 如果用户手机号存在则不需要绑定
            // TODO 校验用户身份信息是否完善
            return redirect()->route('home');
        }
    }

    /**
     * 创建用户信息页面
     * @return [type] [description]
     */
    public function create(Request $request, Auth $auth)
    {
        if ($auth->can('create', $auth)) {

            return view('user/create', compact('auth'));
        } else {

            return abort(403);
        }
    }

    /**
     * 创建用户信息接口
     * @return [type] [description]
     */
    public function createOrUpdate(Request $request, Auth $auth)
    {
        if ($auth->can('create', $auth)) {
            // 数据校验
            $validator = Validator::make($request->all(), [
                'real_name' => 'required|min:2',
                'sex' => 'required|numeric|between:0,1',
                'id_card' => 'required|min:18',
                'city_id' => 'required|numeric|min:2',
                'city_name' => 'required|min:2',
                'business_name' => 'required|min:2',
                // 'id_card_photo_front' => 'required|url',
                // 'id_card_photo_reverse' => 'required|url'
            ]);

            // 校验是否通过
            if (!$validator->fails()) {
                // 创建用户
                $user = User::create(array_merge([
                    'mobile' => $auth->mobile
                ], request([
                    'real_name',
                    'sex',
                    'id_card',
                    'city_id',
                    'city_name',
                    'business_name',
                    'user_business_code'
                    // 'id_card_photo_front',
                    // 'id_card_photo_reverse'
                ])));

                Log::info('用户创建个人信息', [
                    'auth' => $auth->toArray(),
                    'mobile' => $auth->mobile,
                    'user' => $user->toArray()
                ]);

                return redirect()->route('home')->with('messages', '更新成功');
            } else {
                return back()->withInput()->withErrors($validator->errors());
            }
        } else {

            return abort(403);
        }
    }

    /**
     * 修改信息页面
     * @return [type] [description]
     */
    public function edit(Request $request, User $user)
    {
        if ($user->can('view', $user)) {

            return view('user/edit', compact('user'));
        } else {

            Log::warning('非法查看', [
                'from' => session('auth'),
                'to' => $user->toArray()
            ]);

            abort(403);
        }
    }

    /**
     * 用户信息更新
     * @return [type] [description]
     */
    public function update(Request $request, User $user)
    {
        // 用户授权
        if ($user->can('update', $user)) {
            // 数据校验
            $this->validate(request(), [
                // 'mobile' => ['required', new Mobile],
                'real_name' => 'required|min:2',
                'sex' => 'required|numeric|between:0,1',
                'id_card' => 'required|min:18',
                'city_id' => 'required|numeric|min:2',
                'city_name' => 'required|min:2',
                'business_name' => 'required|min:2',
                // 'id_card_photo_front' => 'required|url',
                // 'id_card_photo_reverse' => 'required|url'
            ]);

            // 记录修改前的信息
            $oldUser = $user->toArray();

            // 更新
            $user->update(request([
                // 'mobile',
                'real_name',
                'sex',
                'id_card',
                'city_id',
                'city_name',
                'business_name',
                'user_business_code'
                // 'id_card_photo_front',
                // 'id_card_photo_reverse'
            ]));

            Log::info('用户更新个人信息', [
                'from' => $oldUser,
                'to' => $user->toArray()
            ]);

            return redirect()->route('userinfo', ['user' => $user->id])->with('messages', '更新成功');
        } else {

            Log::warning('非法更新', [
                'from' => session('auth'),
                'to' => $user->toArray()
            ]);

            return abort(403);
        }
    }

}
