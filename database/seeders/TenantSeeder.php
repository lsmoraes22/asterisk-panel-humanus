<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\TenantService;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(TenantService::class);

        $service->createTenant([
            'name' => 'Tenant Inicial',
            'domain' => 'pbx.seudominio.com.br',
            'external_signaling_address' => '189.20.30.40',
            'external_media_address' => '189.20.30.40',
            'local_net' => '192.168.0.0/24',
            'max_endpoints' => 20,
            'max_queues' => 5,
            'max_channels' => 50,
            'timezone' => 'America/Sao_Paulo',
            'active' => true,
        ]);
    }
}
