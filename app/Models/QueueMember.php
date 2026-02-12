<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class QueueMember extends Model
{
    use HasEvents;
    protected $guarded = ['tenant_id'];

    protected $fillable = [
        'tenant_id',
        'queue_id',
        'endpoint_id',
        'penalty',
        'state_interface',
        'paused',
        'membername',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

    public function endpoint()
    {
        return $this->belongsTo(PjsipEndpoint::class, 'endpoint_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

    	static::creating(function ($model) {
            // Pega o tenant da queue automaticamente
            if ($model->queue_id && !$model->tenant_id) {
            	$model->tenant_id = \App\Models\Queue::find($model->queue_id)?->tenant_id;
            }
    	});
    }

}
