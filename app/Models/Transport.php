<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class Transport extends Model
{
    use HasEvents;
    protected $fillable = [
        'tenant_id',
        'name',
        'protocol',
	    'bind'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

}
