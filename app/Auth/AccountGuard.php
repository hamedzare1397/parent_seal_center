<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

/**
 * گارد سفارشی
 * کنترل می‌کند:
 * - آیا کاربر لاگین است؟
 * - آیا اجازه ورود دارد؟
 */
class AccountGuard implements Guard
{
    protected $user;
    protected $provider;
    protected $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    /**
     * گرفتن کاربر لاگین شده
     */
    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $id = session('account_id');

        if ($id) {
            $this->user = $this->provider->retrieveById($id);
        }

        return $this->user;
    }

    /**
     * بررسی لاگین بودن
     */
    public function check()
    {
        return !is_null($this->user());
    }

    /**
     * بررسی لاگین نبودن
     */
    public function guest()
    {
        return !$this->check();
    }

    /**
     * ورود کاربر
     */
    public function attempt(array $credentials)
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if (!$user) {
            return false;
        }

        // بررسی فعال بودن حساب
        if (!$user->is_active) {
            return false;
        }

        if ($this->provider->validateCredentials($user, $credentials)) {
            $this->login($user);
            return true;
        }

        return false;
    }

    /**
     * لاگین کردن کاربر
     */
    public function login($user)
    {
        session(['account_id' => $user->id]);
        $this->user = $user;
    }

    /**
     * خروج کاربر
     */
    public function logout()
    {
        session()->forget('account_id');
        $this->user = null;
    }

    public function id()
    {
        return optional($this->user())->id;
    }

    public function validate(array $credentials = [])
    {
        return $this->attempt($credentials);
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function hasUser()
    {
        // TODO: Implement hasUser() method.
    }
}
