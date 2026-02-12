<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class AsteriskHttp extends Model
{
    use HasEvents;
    protected $table = 'asterisk_http';

    protected $fillable = [
        'tenant_id',
        'enabled',
        'bindaddr',
        'bindport',
        'prefix',
        'sessioncookies',
    ];
}
