<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class DidNumber extends Model
{
    use HasEvents;
    protected $fillable = [
        'tenant_id',
        'number',
        'description',
        'destination',
        'destination_type',
        'active',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
