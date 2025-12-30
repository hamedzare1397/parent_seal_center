<?php
namespace App\Security;

use App\Models\AccessPolicy;
use App\Models\Permission;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AccessPolicyEvaluator
{
    public static function allows(
        $policies,
        Permission $permission,
        string $ip
    ): bool {

        $now = now();

        $policies = $policies
            ->where('permission_id', $permission->id)
            ->sortByDesc('priority');

        foreach ($policies as $policy) {

            if (! self::matchDateTime($policy, $now)) {
                continue;
            }

            if (! self::matchDayTime($policy, $now)) {
                continue;
            }

            if (! self::matchIp($policy, $ip)) {
                continue;
            }

            return (bool) $policy->is_allowed;
        }

        // اگر policy وجود داشت ولی match نشد → deny
        return $policies->isEmpty();
    }

    protected static function matchIp($policy, string $ip): bool
    {
        if (! $policy->ip_ranges) {
            return true;
        }

        foreach ($policy->ip_ranges as $range) {
            if (ip_in_range($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    protected static function matchDateTime($policy, Carbon $now): bool
    {
        if ($policy->starts_at && $now->lt($policy->starts_at)) {
            return false;
        }

        if ($policy->ends_at && $now->gt($policy->ends_at)) {
            return false;
        }

        return true;
    }

    protected static function matchDayTime($policy, Carbon $now): bool
    {
        if ($policy->days_of_week) {
            if (! in_array($now->dayOfWeekIso, $policy->days_of_week)) {
                return false;
            }
        }

        if ($policy->start_time && $now->format('H:i:s') < $policy->start_time) {
            return false;
        }

        if ($policy->end_time && $now->format('H:i:s') > $policy->end_time) {
            return false;
        }

        return true;
    }
}
