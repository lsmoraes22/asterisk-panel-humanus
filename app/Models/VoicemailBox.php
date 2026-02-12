<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class VoicemailBox extends Model
{
    use HasEvents;
    protected $fillable = [
        'tenant_id',
        'mailbox',
        'password',
        'name',
	'email',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

}
