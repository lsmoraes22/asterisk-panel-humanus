<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class Dialplan extends Model
{
	use HasEvents;
    public $fillable = [
	 'tenant_id',
	 'context',
	 'exten',
	 'priority',
	 'application',
	 'app_data',
    ];

    public function tenant()
    {
    	return $this->belongsTo(Tenant::class);
    }

}
