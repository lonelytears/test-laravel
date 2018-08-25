<?php

namespace App\Policies;

use App\Auth;

class AuthPolicy
{
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Auth 查看
     * @param  Auth   $auth [description]
     * @return [type]       [description]
     */
    public function view(Auth $auth)
    {
        return session('auth')['id'] == $auth->id && empty($auth->mobile);
    }

    /**
     * Auth 绑定手机号
     * @return [type] [description]
     */
    public function binding(Auth $auth)
    {
        return session('auth')['id'] == $auth->id && empty($auth->mobile);
    }

    /**
     * Auth 创建
     * @return [type] [description]
     */
    public function create(Auth $auth)
    {
        return session('auth')['id'] == $auth->id && !empty($auth->mobile);
    }

    /**
     * Auth 更新
     * @return [type] [description]
     */
    public function update(Auth $auth)
    {
        return session('auth')['id'] == $auth->id;
    }

    /**
     * Auth 删除
     * @param  Auth   $auth [description]
     * @return [type]       [description]
     */
    public function delete(Auth $auth)
    {
        return false;
    }
}
