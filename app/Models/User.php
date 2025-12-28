<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $appends = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withPivot(['started_at', 'ended_at'])
            ->wherePivotNull('ended_at');
    }

    public function rolesHistory()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withPivot(['started_at', 'ended_at'])
            ->withTimestamps();
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }


    public function groups()
    {
        return $this->morphToMany(Group::class, 'groupable');
    }

    public function accessPolicies()
    {
        return $this->morphMany(AccessPolicy::class, 'policyable');
    }
}
