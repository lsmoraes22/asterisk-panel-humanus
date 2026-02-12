<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class ManagerUser extends Model
{
    use HasEvents;
    protected $table = 'manager_users';

    protected $fillable = [
        'tenant_id',
        'username',
        'secret',
        'read',
        'write',
        'deny',
        'permit',
    ];
}
