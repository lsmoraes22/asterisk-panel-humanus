<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class DialplanExtension extends Model
{
    use HasEvents;
    protected $fillable = [
        'tenant_id',
        'type',
        'extension',
        'endpoint_id',
        'queue_id',
        'timeout',
    ];

    protected $casts = [
        'timeout' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(PjsipEndpoint::class, 'endpoint_id');
    }

    public function queue(): BelongsTo
    {
        return $this->belongsTo(Queue::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isInternal(): bool
    {
        return $this->type === 'internal';
    }

    public function isQueue(): bool
    {
        return $this->type === 'queue';
    }
}

