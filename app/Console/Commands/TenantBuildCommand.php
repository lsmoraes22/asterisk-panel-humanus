<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Observers\TenantObserver;

class TenantBuildCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:build {code}';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Reconstrói toda a estrutura de um tenant específico no Asterisk';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $code = $this->argument('code');

        $tenant = Tenant::where('code', $code)->first();

        if (!$tenant) {
            $this->error("Tenant {$code} não encontrado.");
            return Command::FAILURE;
        }

        $this->info("Reconstruindo estrutura do {$code}...");

        $observer = new TenantObserver();
        $observer->created($tenant);

        $this->info("Tenant {$code} reconstruído com sucesso!");

        return Command::SUCCESS;
    }
}
