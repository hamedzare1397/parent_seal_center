<?php

namespace App\Models;

use App\Models\Auth\AccessPolicy;
use App\Models\Auth\Group;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function usersHistory()
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->withPivot(['started_at', 'ended_at'])
            ->withTimestamps();
    }

    public function currentUser()
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->wherePivotNull('ended_at')
            ->limit(1);
    }


    public function groups()
    {
        return $this->morphToMany(Group::class, 'groupable');
    }

    public function accessPolicies()
    {
        return $this->morphMany(AccessPolicy::class, 'policyable');
    }

    public function orgUnit()
    {
        return $this->belongsTo(Organization::class);
    }
}
