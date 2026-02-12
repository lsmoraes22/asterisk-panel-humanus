<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class QueueRule extends Model
{
    use HasFactory;
    use HasEvents;

    protected $table = 'queue_rules';

    protected $fillable = [
        'tenant_id',
        'name',
        'steps',
        'description',
    ];

    protected $casts = [
        'steps' => 'array',
    ];

    /**
     * Multi-tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Uma regra pode ser usada por várias filas (queues)
     */
    public function queues()
    {
        return $this->hasMany(Queue::class, 'queue_rule_id');
    }
}
