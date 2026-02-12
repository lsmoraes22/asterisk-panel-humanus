<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class TenantList extends Command
{
    protected $signature = 'tenant:list';
    protected $description = 'Lista todos os tenants registrados no sistema';

    public function handle()
    {
        $tenants = Tenant::all(['id', 'code', 'name', 'domain', 'active', 'created_at']);

        if ($tenants->isEmpty()) {
            $this->warn("Nenhum tenant encontrado.");
            return;
        }

        $this->table(
            ['ID', 'Código', 'Nome', 'Domínio', 'Ativo', 'Criado em'],
            $tenants->map(function ($t) {
                return [
                    $t->id,
                    $t->code,
                    $t->name,
                    $t->domain,
                    $t->active ? 'Sim' : 'Não',
                    $t->created_at->format('Y-m-d H:i:s'),
                ];
            })
        );
    }
}
