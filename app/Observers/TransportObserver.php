<?php

namespace App\Observers;

use App\Models\Transport;
use Illuminate\Support\Facades\DB; // IMPORTAÇÃO OBRIGATÓRIA

class TransportObserver
{
    public function created(Transport $transport): void
    {
        DB::connection('asterisk')->table('ps_transports')->insert([
            'id'       => $transport->name,
            //'type'     => 'transport', // Garante que o tipo permaneça correto
            'protocol' => $transport->protocol,
            'bind'     => $transport->bind ?: '0.0.0.0:5060',
            'external_media_address' => $transport->tenant?->external_media_address,
            'external_signaling_address' => $transport->tenant?->external_signaling_address,
        ]);
        
        // Dica: Transports geralmente exigem reload do módulo para aplicar
        shell_exec('asterisk -rx "pjsip reload"');
    }

    public function updated(Transport $transport): void
    {
        DB::connection('asterisk')->table('ps_transports')
            ->where('id', $transport->getOriginal('name'))
            ->update([
                'id'       => $transport->name,
                //'type'     => 'transport', // Garante que o tipo permaneça correto
                'protocol' => $transport->protocol,
                'bind'     => $transport->bind,
                'external_media_address' => $transport->tenant?->external_media_address,
                'external_signaling_address' => $transport->tenant?->external_signaling_address,
            ]);
    }

    public function deleted(Transport $transport): void
    {
        DB::connection('asterisk')->table('ps_transports')
            ->where('id', $transport->name)
            ->delete();
    }
}