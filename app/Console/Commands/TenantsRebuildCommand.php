<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use app\Observers\TenantObserver;
use app\Models\Tenant;

class TenantsRebuildCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'tenants:rebuild';
    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Reconstrói a estrutura de todos os tenants existentes';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Iniciando reconstrução de todos os tenants...");

        $observer = new TenantObserver();

        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->warn("Nenhum tenant encontrado.");
            return Command::SUCCESS;
        }

        foreach ($tenants as $tenant) {
            $this->info("➡️ Reconstruindo {$tenant->code}...");
            $observer->created($tenant);
        }

        $this->info("Todos os tenants foram reconstruídos com sucesso!");

        return Command::SUCCESS;
    }
}
