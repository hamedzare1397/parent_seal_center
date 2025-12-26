<?php

namespace App\Auth;

use App\Models\Auth\Token;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

/**
 * این کلاس وظیفه ارتباط با دیتابیس را دارد
 * یعنی: پیدا کردن کاربر و اعتبارسنجی رمز عبور
 */
class AccountUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return User::find($identifier);
    }

    public function retrieveByToken($identifier, #[\SensitiveParameter] $token)
    {
        $tokens=Token::where('account_id', $identifier)->get();
        foreach ($tokens as $record) {
            if ($record->isExpired()) {
                continue;
            }

            if (Hash::check($token, $record->remember_token)) {
                return User::find($identifier);
            }
        }

        return null;

    }

    public function updateRememberToken(Authenticatable $user, #[\SensitiveParameter] $token)
    {
        Token::create([
            'user_id' => $user->getAuthIdentifier(),
            'remember_token' => Hash::make($token),
            'expires_at' => now()->addMinutes(30),
        ]);
    }

    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials)
    {
        return User::where('username',$credentials['username'])->first();
    }

    public function validateCredentials(Authenticatable $user, #[\SensitiveParameter] array $credentials)
    {
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }


    /**
     * Rehash password if needed after successful authentication
     *
     * @param Authenticatable $user
     * @param array $credentials
     * @param bool $force  Force rehash even if not required
     */
    public function rehashPasswordIfRequired(
        Authenticatable $user,
        #[\SensitiveParameter] array $credentials,
        bool $force = false
    ): void
    {
        // اگر پسورد در ورودی وجود ندارد، کاری نکن
        if (! isset($credentials['password'])) {
            return;
        }

        $currentHash = $user->getAuthPassword();

        // اگر hash نیاز به بروزرسانی دارد یا force شده
        if ($force || Hash::needsRehash($currentHash)) {

            // ساخت hash جدید با تنظیمات فعلی config/hashing.php
            $newHash = Hash::make($credentials['password']);

            /**
             * نکته مهم:
             * نام ستون را مطابق دیتابیس خودت تنظیم کن
             * اینجا: password_hash
             */
            $user->forceFill([
                'password' => $newHash,
            ]);

            // ذخیره بدون لمس timestamps اضافی (اختیاری)
            $user->save();
        }
    }

}
