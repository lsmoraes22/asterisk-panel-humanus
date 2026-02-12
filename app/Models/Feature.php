<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class Feature extends Model
{
    use HasEvents;
    protected $fillable = [
         'tenant_id',
         'name',
         'code',
         'enabled',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

}
