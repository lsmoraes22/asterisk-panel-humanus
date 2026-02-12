<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;

class AsteriskTenantService
{
    public function createTenantConfig(Tenant $tenant)
    {
        try {
            $basePath = "/etc/asterisk/tenants/tenant-{$tenant->code}";

            // 1. Garante a estrutura de pastas conforme sua árvore
            if (!File::exists("{$basePath}/dialplan")) {
                File::makeDirectory("{$basePath}/dialplan", 0775, true);
            }

            // 2. Prepara os dados (Certifique-se que os relacionamentos existem no Model Tenant)
            $data = [
                'tenant'      => $tenant,
                'endpoints'   => $tenant->extensions, // Ajuste para o nome da relação no seu Model
                'queues'      => $tenant->queues ?? [],
                'did_numbers' => $tenant->did_numbers ?? []
            ];

            // 3. Renderiza e salva os arquivos (Ajustado para os nomes das suas blades)
            // extensions.conf do tenant
            File::put("{$basePath}/extensions.conf", view('asterisk.extensions', $data)->render());

            // dialplan/internal.conf
            File::put("{$basePath}/dialplan/internal.conf", view('asterisk.internal', $data)->render());

            // dialplan/incoming.conf
            File::put("{$basePath}/dialplan/incoming.conf", view('asterisk.incoming', $data)->render());

            // musiconhold.conf
            File::put("{$basePath}/musiconhold.conf", view('asterisk.musiconhold', $data)->render());

            // 4. Ajusta permissões para o Asterisk conseguir ler
            shell_exec("sudo chown -R asterisk:asterisk {$basePath}");
            shell_exec("sudo chmod -R 775 {$basePath}");

            // 5. Recarrega o Asterisk
            shell_exec('asterisk -rx "dialplan reload"');
            shell_exec('asterisk -rx "moh reload"');

            Log::info("Configurações do Tenant {$tenant->code} geradas com sucesso.");

        } catch (\Exception $e) {
            Log::error("Erro ao gerar config do Asterisk: " . $e->getMessage());
            throw $e;
        }
    }
}