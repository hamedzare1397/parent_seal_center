<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
 use HasFactory;
    public function parent()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    // برای Policy inheritance
    public function ancestors()
    {
        return $this->parent
            ? collect([$this->parent])->merge($this->parent->ancestors())
            : collect();
    }

    public function accessPolicies()
    {
        return $this->morphMany(AccessPolicy::class, 'policyable');
    }
}
