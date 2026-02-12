<?php

namespace App\Services;

use App\Models\Tenant;

class TenantService
{
    /**
     * Gera o próximo código sequencial no formato tenant-0001
     */
    public function generateNextCode(): string
    {
        $lastTenant = Tenant::orderBy('id', 'desc')->first();

        if (!$lastTenant) {
            return 'tenant-0001';
        }

        $lastCode = $lastTenant->code; // ex: tenant-0042

        $number = intval(substr($lastCode, -4)); // extrai "0042"
        $next = $number + 1;

        return 'tenant-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Cria um tenant com code automático
     */
    public function createTenant(array $data): Tenant
    {
        $data['code'] = $this->generateNextCode();

        return Tenant::create($data);
    }
}
