<?php

namespace App\Observers;

use App\Models\DidNumber;
use App\Services\AsteriskTenantService;

class DidNumberObserver
{
    // O Laravel passa automaticamente a instância do DID que foi criado
    public function created(DidNumber $did)
    {
        $this->syncAsterisk($did);
    }

    public function updated(DidNumber $did)
    {
        $this->syncAsterisk($did);
    }

    public function deleted(DidNumber $did)
    {
        $this->syncAsterisk($did);
    }

    // Centralizamos a lógica para não repetir código
    protected function syncAsterisk(DidNumber $did)
    {
        // Pegamos o tenant diretamente da relação do Model
        $tenant = $did->tenant;

        if ($tenant) {
            app(AsteriskTenantService::class)->createTenantConfig($tenant);
            shell_exec('asterisk -rx "Dialplan reload"');
        }
    }
}