<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class Queue extends Model
{
    use HasEvents;
    protected $fillable = [
        'tenant_id',
        'name',
        'strategy',
        'timeout',
    	'musicclass',
        'retry',
        'wrapuptime',
        'maxlen',
        'announce_frequency',
        'announce_holdtime',
        'announce_position',
        'joinempty',
        'leavewhenempty',
        'ringinuse',
        'timeoutrestart',
        'weight',
        'setinterfacevar',
        'setqueuevar',
        'setqueueentryvar',
        'reportholdtime',
        'announce_override',
        'announce_round_seconds',
        'context',
        'monitor_format',
        'memberdelay',
        'autopause',
        'autopausedelay',
        'autopausebusy',
        'autopauseunavail',
        'penaltymemberslimit',
        'penaltytimeout',
        'penaltytimerepeat',
        'queue_rule_id'
    ];


public $casts = [
    'announce_holdtime' => 'boolean',
    'announce_position' => 'boolean',   // <=== corrigido
    'joinempty' => 'boolean',
    'leavewhenempty' => 'boolean',
    'ringinuse' => 'boolean',
    'timeoutrestart' => 'boolean',
    'setinterfacevar' => 'boolean',
    'setqueuevar' => 'boolean',
    'setqueueentryvar' => 'boolean',
    'reportholdtime' => 'boolean',
    'autopause' => 'boolean',
    'autopausebusy' => 'boolean',
    'autopauseunavail' => 'boolean',
];


    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function queueRule()
    {
        return $this->belongsTo(QueueRule::class);
    }

    public function members()
    {
    	return $this->hasMany(QueueMember::class);
    }

}
