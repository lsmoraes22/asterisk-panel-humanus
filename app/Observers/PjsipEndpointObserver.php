<?php

namespace App\Observers;

use App\Models\PjsipEndpoint;
use Illuminate\Support\Facades\DB;

class PjsipEndpointObserver
{
    /**
     * Centraliza os dados comuns para evitar repetição no created/updated.
     */
    private function getAsteriskData(PjsipEndpoint $endpoint): array
    {
        return [
            'id'              => $endpoint->id,
            'transport'       => $endpoint->transport ?? 'transport-udp',
            'aors'            => $endpoint->id,
            'auth'            => $endpoint->id,
            'mailboxes'       => $endpoint->mailboxes,
            'context'         => $endpoint->context ?? "ctx-{$endpoint->tenant->code}-internal",
            'disallow'        => 'all',
            'allow'           => $endpoint->allow ?? 'opus,alaw,ulaw,g722',
            'direct_media'    => 'no',
            'rewrite_contact' => 'yes',
            'rtp_symmetric'   => 'yes',
            'force_rport'     => 'yes',
            'accountcode'     => $endpoint->tenant->code,
            'callerid'        => "{$endpoint->name} <{$endpoint->id}>",
        ];
    }

    public function created(PjsipEndpoint $endpoint)
    {
        $db = DB::connection('asterisk');

        // 1. Criar o Endpoint Principal
        $db->table('ps_endpoints')->insert($this->getAsteriskData($endpoint));

        // 2. Se for Linha/Tronco (Identificação por IP)
        if (!empty($endpoint->match)) {
            $db->table('ps_endpoint_id_ips')->insert([
                'id'       => $endpoint->id . '-ip',
                'endpoint' => $endpoint->id,
                'match'    => $endpoint->match,
            ]);
        }
    }

    public function updated(PjsipEndpoint $endpoint)
    {
        $db = DB::connection('asterisk');
        $oldId = $endpoint->getOriginal('id');

        // Se o ID (ramal) mudou, precisamos rotacionar as chaves estrangeiras no Asterisk
        if ($oldId !== $endpoint->id) {
            $db->table('ps_endpoints')->where('id', $oldId)->delete();
            $db->table('ps_endpoint_id_ips')->where('endpoint', $oldId)->delete();
            
            // Recria com o novo ID
            $this->created($endpoint);
            return;
        }

        // 1. Atualiza Endpoint
        $db->table('ps_endpoints')->where('id', $endpoint->id)->update([
            'context'   =>  "ctx-{$endpoint->tenant->code}-{$endpoint->context}",
            'callerid'  => "{$endpoint->name} <{$endpoint->id}>",
            'mailboxes' => $endpoint->mailboxes,
            'allow'     => $endpoint->allow ?? 'opus,alaw,ulaw,g722',
        ]);

        // 2. Sincroniza Identificação por IP (ps_endpoint_id_ips)
        if (!empty($endpoint->match)) {
            $db->table('ps_endpoint_id_ips')->updateOrInsert(
                ['id' => $endpoint->id . '-ip'],
                ['endpoint' => $endpoint->id, 'match' => $endpoint->match]
            );
        } else {
            $db->table('ps_endpoint_id_ips')->where('id', $endpoint->id . '-ip')->delete();
        }
    }

    public function deleted(PjsipEndpoint $endpoint)
    {
        $db = DB::connection('asterisk');

        // Remove de todas as tabelas relacionadas no Asterisk
        $db->table('ps_endpoints')->where('id', $endpoint->id)->delete();
        $db->table('ps_endpoint_id_ips')->where('id', $endpoint->id . '-ip')->delete();
        
        // Remove registros de contatos ativos (limpa o "online" do ramal)
        $db->table('ps_contacts')->where('id', 'like', $endpoint->id . '%')->delete();
    }
}