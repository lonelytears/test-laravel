<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Auth extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    /**
     * 微信用户关联的用户信息
     * @return [type] [description]
     */
    public function user()
    {
        return $this->hasOne('App\User', 'mobile', 'mobile');
    }

    /**
     * 获取已经登录用户
     * @return [type] [description]
     */
    public static function getLoginAuth()
    {
        return self::find(session('auth')['id']);
    }

    /**
     * 获取当前登录 Auth 是否绑定手机号
     * @return boolean [description]
     */
    public static function hasBindingMobile()
    {
        return !empty(self::find(session('auth')['id'])->mobile);
    }
}
