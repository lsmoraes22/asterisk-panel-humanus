<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class PjsipEndpoint extends Model
{
    use HasEvents;
    // Desativa o auto-incremento
    public $incrementing = false;
    // Define que a chave é uma string (o nome/número do ramal)
    protected $keyType = 'string';
    
    protected $fillable = [
        'id', //ramal
        'tenant_id',
        'name',
        'endpoint',
        'match',
        'auth',
        'mailboxes',
        'aor',
	    'context',
	    'transport',
	    'allow',

    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function queueMember()
    {
        return $this->belongsTo(QueueMember::class);
    }

}
