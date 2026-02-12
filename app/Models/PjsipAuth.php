<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class PjsipAuth extends Model
{
    use HasEvents;
    // Desativa o auto-incremento
    public $incrementing = false;
    // Define que a chave é uma string (o nome/número do ramal)
    protected $keyType = 'string';

    protected $fillable = [
        'id',   //ramal
        'tenant_id',
        'name',
        'type',
        'username',
	    'password'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

}
