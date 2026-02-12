<?php

namespace App\Observers;

use App\Models\DialplanExtension;
use Illuminate\Support\Facades\DB;

class DialplanExtensionObserver
{
    public function saved(DialplanExtension $dialplan): void
    {
        $this->syncToAsterisk($dialplan);
    }

    public function deleted(DialplanExtension $dialplan): void
    {
        DB::connection('asterisk')->table('extensions')
            ->where('context', "{$dialplan->tenant->code}-internal")
            ->where('exten', $dialplan->extension)
            ->delete();
            
        $this->reloadDialplan();
    }

    protected function syncToAsterisk(DialplanExtension $dialplan): void
    {
        $db = DB::connection('asterisk');
        $context = "{$dialplan->tenant->code}-{$dialplan->type}";
        
        // Limpa antes de inserir para evitar duplicidade de prioridades
        $db->table('extensions')
            ->where('context', $context)
            ->where('exten', $dialplan->extension)
            ->delete();

        if ($dialplan->type === 'internal' && $dialplan->endpoint_id) {
            // Lógica para discar para Ramal
            $db->table('extensions')->insert([
                'context' => $context,
                'exten' => $dialplan->extension,
                'priority' => 1,
                'app' => 'Dial',
                'appdata' => "PJSIP/{$dialplan->endpoint_id},{$dialplan->timeout}",
            ]);
        } 
        elseif ($dialplan->type === 'queue' && $dialplan->queue_id) {
            // Lógica para discar para Fila
            $db->table('extensions')->insert([
                'context' => $context,
                'exten' => $dialplan->extension,
                'priority' => 1,
                'app' => 'Answer',
                'appdata' => '',
            ]);
            $db->table('extensions')->insert([
                'context' => $context,
                'exten' => $dialplan->extension,
                'priority' => 2,
                'app' => 'Queue',
                'appdata' => $dialplan->queue->name, // Assume que o Model Queue tem 'name'
            ]);
        }

        $this->reloadDialplan();
    }

    private function reloadDialplan(): void
    {
        // Como é Realtime Static (tabela extensions), o reload é necessário 
        // para limpar o cache do switch.
        shell_exec("asterisk -rx 'dialplan reload'");
    }
}
/** */