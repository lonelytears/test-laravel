<?php

namespace App\Policies;

use App\User;
use App\Auth;

class UserPolicy
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

    public function view(User $user)
    {
        return session('auth')['mobile'] === $user->mobile;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user)
    {
        return session('auth')['mobile'] === $user->mobile;
    }

    public function delete(User $user)
    {
        return session('auth')['mobile'] === $user->mobile;
    }
}
