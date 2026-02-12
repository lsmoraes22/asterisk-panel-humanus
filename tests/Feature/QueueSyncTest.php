<?php
namespace Tests\Feature;

use App\Models\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QueueSyncTest extends TestCase
{
    use RefreshDatabase; // Limpa o banco a cada teste

    /** @test */
    public function it_syncs_queue_to_asterisk_database()
    {
        // 1. Criamos uma fila no banco do Laravel (disparando o Observer)
        $queue = Queue::create([
            'name' => 'Fila_Vendas',
            'strategy' => 'ringall',
            'timeout' => 20,
            'musiconhold' => 'default',
        ]);

        // 2. Verificamos se o dado apareceu na conexão 'asterisk' (tabela queues_config)
        $this->assertDatabaseHas('queues_config', [
            'name' => 'Fila_Vendas',
            'strategy' => 'ringall'
        ], 'asterisk'); // O terceiro parâmetro é a conexão
    }

    /** @test */
    public function it_generates_correct_dialplan_priorities()
    {
        // Criamos uma regra de Dialplan
        $dialplan = \App\Models\Dialplan::create([
            'context' => 'internal',
            'exten' => '100',
            'destination_type' => 'queue',
            'destination_value' => 'Fila_Suporte'
        ]);

        // Verificamos se gerou as 3 linhas de prioridade (Answer, Queue, Hangup)
        $this->assertDatabaseCount('extensions', 3, 'asterisk');
        
        $this->assertDatabaseHas('extensions', [
            'context' => 'internal',
            'exten' => '100',
            'priority' => 2,
            'app' => 'Queue'
        ], 'asterisk');
    }
}