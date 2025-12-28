<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AccessPolicy extends Model
{
protected $fillable = [
'permission_id',
'is_allowed',
'ip_ranges',
'starts_at',
'ends_at',
'days_of_week',
'start_time',
'end_time',
'timezone',
'priority',
];

protected $casts = [
'ip_ranges'    => 'array',
'days_of_week' => 'array',
'starts_at'    => 'datetime',
'ends_at'      => 'datetime',
];

/* ---------------- Relations ---------------- */

public function permission()
{
return $this->belongsTo(Permission::class);
}

public function policyable(): MorphTo
{
return $this->morphTo();
}

/* ---------------- Policy Logic ---------------- */

public function matchesTime(Carbon $now): bool
{
$now = $now->setTimezone($this->timezone);

if ($this->starts_at && $now->lt($this->starts_at)) {
return false;
}

if ($this->ends_at && $now->gt($this->ends_at)) {
return false;
}

if ($this->days_of_week) {
if (! in_array($now->dayOfWeekIso, $this->days_of_week)) {
return false;
}
}

if ($this->start_time && $this->end_time) {
$time = $now->format('H:i:s');

if ($time < $this->start_time || $time > $this->end_time) {
return false;
}
}

return true;
}

public function matchesIp(string $ip): bool
{
if (empty($this->ip_ranges)) {
return true;
}

return \App\Support\IpMatcher::matches($ip, $this->ip_ranges);
}

public function isApplicable(Carbon $now, string $ip): bool
{
return $this->matchesTime($now)
&& $this->matchesIp($ip);
}
}
