<?php
// app/Models/Group.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;

class Group extends Model
{
protected $fillable = [
'name',
'label',
];

public function users(): MorphedByMany
{
return $this->morphedByMany(User::class, 'groupable');
}

public function roles(): MorphedByMany
{
return $this->morphedByMany(Role::class, 'groupable');
}

public function permissions()
{
return $this->belongsToMany(Permission::class);
}

public function accessPolicies()
{
return $this->morphMany(AccessPolicy::class, 'policyable');
}
}
