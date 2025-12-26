<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'ip_address','platform','device_name','device_id',
        'browser','revoked_at','last_used_at',
        'token_hash','account_id','expires_at','remember_token',
        ];

    public function scopeIsExpired($query)
    {
        return !$query->where('expires_at', '<', now())->first();
    }
}
