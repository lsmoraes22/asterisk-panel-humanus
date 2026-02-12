<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Services\AsteriskTenantService;

class TenantSyncConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:sync-config {code}';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Sincroniza/gera toda a estrutura de configuração do Asterisk para um tenant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $code = $this->argument('code');

        $tenant = Tenant::where('code', $code)->first();

        if (!$tenant) {
            $this->error("Tenant '{$code}' não encontrado.");
            return 1;
        }

        $this->info("Sincronizando configuração do tenant: {$tenant->code}...");

        try {
            $service = new AsteriskTenantService();
            $service->syncTenant($tenant); // método principal

            $this->info("✔ Estrutura do Asterisk sincronizada com sucesso!");
        } catch (\Exception $e) {
            $this->error("Erro ao sincronizar: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
