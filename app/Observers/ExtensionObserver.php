<?php

namespace App\Observers;

use App\Models\Extension;
use App\Services\AsteriskTenantService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExtensionObserver
{
    public function __construct(protected AsteriskTenantService $tenantService) {}

    public function saved(Extension $extension): void
    {
        try {
            $tenant = $extension->tenant;
            if (!$tenant) {
                Log::error("Extension {$extension->number} saved without a tenant.");
                return;
            }

            // Identificador único para o ramal no Asterisk para evitar conflitos
            // Formato: tenantcode_ramal (ex: 101_1000)
            $asteriskId = "{$tenant->code}_{$extension->number}";

            $asterisk = DB::connection('asterisk');

            // 1. Sincroniza o PJSIP no Banco (Realtime)
            // Endpoint
            $asterisk->table('ps_endpoints')->updateOrInsert(['id' => $asteriskId], [
                'transport' => 'transport-udp',
                'aors' => $asteriskId,
                'auth' => $asteriskId,
                'context' => "ctx-{$tenant->code}-from-internal",
                'disallow' => 'all',
                'allow' => 'ulaw,alaw,opus',
                'rewrite_contact' => 'yes',
                'direct_media' => 'no',
            ]);

            // Auth
            $asterisk->table('ps_auths')->updateOrInsert(['id' => $asteriskId], [
                'username' => $asteriskId, // O username SIP será tenantcode_ramal
                'password' => $extension->password,
                'auth_type' => 'userpass',
            ]);

            // AOR
            $asterisk->table('ps_aors')->updateOrInsert(['id' => $asteriskId], [
                'max_contacts' => 5, // Permitir múltiplos registros se necessário
                'remove_existing' => 'yes'
            ]);

            // 2. Sincroniza o Dialplan Realtime para chamadas internas entre ramais do mesmo Tenant
            // Isso garante que o ramal 1000 do Tenant A não ligue para o 1000 do Tenant B
            $asterisk->table('extensions')->updateOrInsert([
                'context' => "ctx-{$tenant->code}-internal-realtime",
                'exten' => $extension->number
            ], [
                'priority' => 1,
                'app' => 'Dial',
                'appdata' => "PJSIP/{$asteriskId},30,TtkW",
            ]);

            // 3. Gera os arquivos de configuração estáticos (se necessário)
            $this->tenantService->createTenantConfig($tenant);
            
            // 4. Recarrega as configurações
            shell_exec("asterisk -rx 'pjsip reload'");
            shell_exec("asterisk -rx 'dialplan reload'");

        } catch (\Exception $e) {
            Log::error("Error in ExtensionObserver@saved: " . $e->getMessage());
        }
    }

    public function deleted(Extension $extension): void
    {
        try {
            $tenant = $extension->tenant;
            if (!$tenant) return;
            
            $asteriskId = "{$tenant->code}_{$extension->number}";
            $asterisk = DB::connection('asterisk');

            // Remove do Banco
            $asterisk->table('ps_endpoints')->where('id', $asteriskId)->delete();
            $asterisk->table('ps_auths')->where('id', $asteriskId)->delete();
            $asterisk->table('ps_aors')->where('id', $asteriskId)->delete();
            $asterisk->table('extensions')->where('context', "ctx-{$tenant->code}-internal-realtime")->where('exten', $extension->number)->delete();

            // Atualiza os arquivos
            $this->tenantService->createTenantConfig($tenant);
            
            shell_exec("asterisk -rx 'pjsip reload'");
            shell_exec("asterisk -rx 'dialplan reload'");
        } catch (\Exception $e) {
            Log::error("Error in ExtensionObserver@deleted: " . $e->getMessage());
        }
    }
}
