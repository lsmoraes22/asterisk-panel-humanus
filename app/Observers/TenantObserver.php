<?php

namespace App\Observers;

use App\Models\Tenant;
use App\Services\TenantService;
use App\Services\AsteriskTenantService;

class TenantObserver
{
    public function creating(Tenant $tenant)
    {
        if (!$tenant->code) {
            $tenant->code = app(TenantService::class)->generateNextCode();
        }
    }

    public function updating(Tenant $tenant)
    {
        if ($tenant->isDirty('code')) {
            $tenant->code = $tenant->getOriginal('code');
        }
    }

    public function created(Tenant $tenant)
    {
        app(AsteriskTenantService::class)->createTenantConfig($tenant);
    }
}
