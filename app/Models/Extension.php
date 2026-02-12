<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    protected $fillable = [
        'number',
        'password',
        'display_name',
        'tenant_id',
        'context',
        'voicemail',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

}
