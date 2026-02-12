<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Queue;
use App\Models\QueueMember;
use App\Models\PjsipEndpoint;
use App\Models\Tenant;

class QueueMemberSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();

        if (!$tenant) {
            return;
        }

        $queue = Queue::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'atendimento',
        ]);

        $endpoints = PjsipEndpoint::take(3)->get();

        foreach ($endpoints as $i => $endpoint) {
            QueueMember::create([
                'tenant_id' => $tenant->id,
                'queue_id' => $queue->id,
                'endpoint_id' => $endpoint->id,
                'penalty' => $i,
                'paused' => 'no',
                'membername' => "Agente {$endpoint->id}",
                'state_interface' => "PJSIP/{$endpoint->id}",
            ]);
        }
    }
}
