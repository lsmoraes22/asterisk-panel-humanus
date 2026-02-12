<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'tenant_id',
        'label',
        'token',
        'ip_address',
        'expires_at',
    ];

    protected static function booted()
    {
        static::creating(fn ($invite) => $invite->token = bin2hex(random_bytes(32)));
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
