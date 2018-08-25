<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Auth;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    // 定义性别常量
    const SEX_BOY = 1;
    const SEX_GIRL = 0;
    const SEX_UN = 2;

    /**
     * 处理性别
     * @param  [type] $ind [description]
     * @return [type]      [description]
     */
    public function sex($ind = null)
    {
        $arr = array(
            self::SEX_GIRL => '女',
            self::SEX_BOY => '男',
            self::SEX_UN => '未知',
        );

        if($ind !== null)
        {
            return array_key_exists($ind, $arr) ? $arr[$ind] : $arr[self::SEX_UN];
        }
        return $arr;
    }

    /**
     * 用户关联看单
     * @return [type] [description]
     */
    public function looks()
    {
        return $this->hasMany(\App\Look::class, 'user_id', 'id');
    }

    /**
     * 获取已登录的用户信息
     * @return [type] [description]
     */
    public static function getLoginUser()
    {
        $auth = Auth::getLoginAuth();
        if (empty($auth->mobile)) {
            return false;
        }

        return self::where(['mobile' => session('auth')['mobile']])->first();
    }

    /**
     * 组装发送验证码
     * @return [type] [description]
     */
    public static function validatecodeSend($mobile, $expire=1)
    {
        // 生成验证码
        $validateCode = mt_rand(100, 999) . '' . mt_rand(100, 999);
        $key = 'PulinApi-validatecode-' . $mobile;

        // 删除缓存
        cache()->forget($key);

        // 设置缓存
        cache()->put($key, $validateCode, now()->addMinute($expire));

        return [
            'tempId' => 10000,
            'var' =>$validateCode,
            'to' => $mobile
        ];
    }

    /**
     * 检查验证码
     * @param  [type] $mobile [description]
     * @return [type]         [description]
     */
    public static function validatecodeCheck($mobile)
    {
        $key = 'PulinApi-validatecode-' . $mobile;
        return cache($key);
    }
}
