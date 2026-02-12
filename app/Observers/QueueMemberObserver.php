<?php

namespace App\Observers;

use App\Models\QueueMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueueMemberObserver
{
    /**
     * Ao adicionar um ramal à fila no painel.
     */
    public function created(QueueMember $member): void
    {
        // O campo 'interface' no Asterisk deve ser PJSIP/numero_do_ramal
        // Vamos assumir que seu model QueueMember tem relação com PjsipEndpoint
        $interface = "PJSIP/{$member->endpoint_name}";

        DB::connection('asterisk')->table('queue_members')->insert([
            'queue_name' => $member->queue_name,
            'interface'  => $interface,
            'membername' => $member->membername ?? $member->endpoint_name,
            'state_interface' => $interface, // Importante para monitorar se o ramal está ocupado
            'penalty'    => $member->penalty ?? 0,
            'paused'     => 0,
        ]);

        $this->reloadQueues();
    }

    /**
     * Ao atualizar dados do membro (como a penalidade).
     */
    public function updated(QueueMember $member): void
    {
        DB::connection('asterisk')->table('queue_members')
            ->where('queue_name', $member->getOriginal('queue_name'))
            ->where('interface', "PJSIP/{$member->getOriginal('endpoint_name')}")
            ->update([
                'queue_name' => $member->queue_name,
                'penalty'    => $member->penalty,
                'paused'     => $member->paused,
            ]);

        $this->reloadQueues();
    }

    /**
     * Ao remover o ramal da fila.
     */
    public function deleted(QueueMember $member): void
    {
        DB::connection('asterisk')->table('queue_members')
            ->where('queue_name', $member->queue_name)
            ->where('interface', "PJSIP/{$member->endpoint_name}")
            ->delete();

        $this->reloadQueues();
    }

    /**
     * Comando para o Asterisk ler as mudanças de membros.
     */
    private function reloadQueues(): void
    {
        // No caso de membros, 'queue reload all' ou 'module reload app_queue.so'
        shell_exec("asterisk -rx 'queue reload all'");
    }
}