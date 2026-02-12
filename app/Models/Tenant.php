<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class Tenant extends Model
{
    use HasEvents;
    protected $fillable = [
        'code',
        'name',
        'domain',
        'external_signaling_address',
        'external_media_address',
        'local_net',
        'max_endpoints',
        'max_queues',
        'max_channels',
        'timezone',
        'active',
    ];

    public function permissions()
    {
        return $this->hasMany(TenantPermission::class);
    }

    public function features() { return $this->hasMany(Feature::class); }
    public function aors() { return $this->hasMany(PjsipAor::class); }
    public function auths() { return $this->hasMany(PjsipAuth::class); }
    public function endpoints() { return $this->hasMany(PjsipEndpoint::class); }
    public function queues() { return $this->hasMany(Queue::class); }
    public function transports() { return $this->hasMany(Transport::class); }
    public function voicemail_boxes() { return $this->hasMany(VoicemailBox::class); }
    public function did_numbers() { return $this->hasMany(DidNumber::class); }
    public function extensions() { return $this->hasMany(Extension::class); }
    public function invites() { return $this->hasMany(Invite::class); }
}
