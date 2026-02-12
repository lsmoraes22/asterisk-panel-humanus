<?php

namespace App\Observers;

use App\Models\Dialplan;
use Illuminate\Support\Facades\File;

class DialplanObserver
{
    /*
    public function saved(Dialplan $dialplan): void
    {
        $this->generatePhysicalConfig($dialplan);
        $this->reloadDialplan();
    }

    protected function generatePhysicalConfig(Dialplan $dialplan): void
    {
        $tenantCode = $dialplan->tenant->code; // Ex: tenant-0001
        $basePath = "/etc/asterisk/tenants/{$tenantCode}/dialplan";
        
        // Decidimos em qual arquivo escrever baseado no contexto ou tipo
        // Ex: contextos internos vão para internal.conf
        $fileName = str_contains($dialplan->context, 'internal') ? 'internal.conf' : 'external.conf';
        $filePath = "{$basePath}/{$fileName}";

        // Pegamos todos os dialplans desse tenant para reconstruir o arquivo
        $dialplans = Dialplan::where('tenant_id', $dialplan->tenant_id)
            ->where('context', $dialplan->context)
            ->get();

        $content = "; Auto-generated Dialplan for Tenant {$tenantCode}\n";
        $content .= "[{$dialplan->context}]\n";

        foreach ($dialplans as $dp) {
            $priorities = $this->buildPrioritySequence($dp);
            foreach ($priorities as $index => $step) {
                $prio = $index + 1;
                $content .= "exten => {$dp->exten},{$prio},{$step['app']}({$step['appdata']})\n";
            }
        }

        // Garante que a pasta existe e escreve o arquivo
        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0755, true);
        }

        File::put($filePath, $content);
    }

    protected function buildPrioritySequence(Dialplan $dialplan): array
    {
        // ... (sua lógica de passos permanece igual)
        $steps = [];
        if ($dialplan->destination_type === 'queue') {
            $steps[] = ['app' => 'Answer', 'appdata' => ''];
            $steps[] = ['app' => 'Queue', 'appdata' => $dialplan->destination_value];
        } else {
            $steps[] = ['app' => 'Dial', 'appdata' => "PJSIP/{$dialplan->destination_value},30"];
        }
        return $steps;
    }

    private function reloadDialplan(): void
    {
        // Se usar arquivos físicos, o reload precisa de permissão de escrita no arquivo pelo Asterisk
        shell_exec("asterisk -rx 'dialplan reload'");
    }
        /**/
}
