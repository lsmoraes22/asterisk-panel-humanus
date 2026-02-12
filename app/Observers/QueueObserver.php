<?php

namespace App\Observers;

use App\Models\Queue;
use Illuminate\Support\Facades\DB;

class QueueObserver
{
    /**
     * Ao criar uma fila no painel, insere no banco do Asterisk.
     */
    public function created(Queue $queue): void
    {
        DB::connection('asterisk')->table('queues')->insert([
            'name'           => $queue->name,
            'musiconhold'    => $queue->musiconhold ?? 'default',
            'announce'       => $queue->announce,
            'context'        => $queue->context ?? "ctx-{$queue->tenant->code}-queues",
            'timeout'        => $queue->timeout ?? 15,
            'monitor_type'   => 'MixMonitor', // Padrão para gravação
            'monitor_format' => 'wav',
            'strategy'       => $queue->strategy ?? 'ringall',
            'joinempty'      => 'yes',
            'leavewhenempty' => 'no',
            'retry' => 5,
            'wrapuptime' => 15,
            'maxlen' => 0,
            'announce_frequency' => 0,
        ]);

        // Notifica o Asterisk para recarregar as filas
        shell_exec("asterisk -rx 'queue reload all'");
    }

    /**
     * Ao atualizar no painel, sincroniza os parâmetros.
     */
    public function updated(Queue $queue): void
    {
        DB::connection('asterisk')->table('queues')
            ->where('name', $queue->getOriginal('name'))
            ->update([
                'name'     => $queue->name,
                'strategy' => $queue->strategy,
                'timeout'  => $queue->timeout,
                'musiconhold' => $queue->musiconhold,
            ]);

        shell_exec("asterisk -rx 'queue reload all'");
    }

    /**
     * Remove a fila do Asterisk ao deletar no painel.
     */
    public function deleted(Queue $queue): void
    {
        DB::connection('asterisk')->table('queues')
            ->where('name', $queue->name)
            ->delete();

        shell_exec("asterisk -rx 'queue reload all'");
    }
}