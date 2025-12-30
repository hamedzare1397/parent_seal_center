<?php
namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Permission;
use App\Security\AccessPolicyEvaluator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticateUser
{
    public function __invoke(Request $request): User
    {
        /* 1️⃣ وجود کاربر */
        $user = User::where('username', $request->username)->first();

        if (! $user || $user->status !== 'active') {
            throw ValidationException::withMessages([
                'username' => 'اطلاعات ورود نادرست است.',
            ]);
        }

        /* 2️⃣ بررسی permission login */
        $permission = Permission::where('name', 'auth.login')->first();

        if (! $permission) {
            throw ValidationException::withMessages([
                'username' => 'مجوز ورود تعریف نشده است.',
            ]);
        }

        /* 3️⃣ جمع‌آوری policyها */
        $policies = collect()
            ->merge($user->accessPolicies)
            ->merge($user->roles->flatMap->accessPolicies)
            ->merge($user->groups->flatMap->accessPolicies);

        /* 4️⃣ بررسی AccessPolicy */
        if (! AccessPolicyEvaluator::allows(
            $policies,
            $permission,
            $request->ip()
        )) {
            throw ValidationException::withMessages([
                'username' => 'ورود در این زمان یا IP مجاز نیست.',
            ]);
        }

        /* 5️⃣ بررسی عضویت role + permission */
        $hasPermission =
            $user->permissions->contains('id', $permission->id)
            || $user->roles->flatMap->permissions->contains('id', $permission->id)
            || $user->groups->flatMap->permissions->contains('id', $permission->id)
            || $user->role->groups->flatMap->permissions->contains('id', $permission->id);

        if (! $hasPermission) {
            throw ValidationException::withMessages([
                'username' => 'شما مجوز ورود به سیستم را ندارید.',
            ]);
        }

        /* 6️⃣ بررسی رمز */
        if (! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'رمز عبور نادرست است.',
            ]);
        }

        return $user;
    }
}
