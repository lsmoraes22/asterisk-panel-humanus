<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\Extension;

class PjsipIntegrationTest extends TestCase
{
    /** @test */
    public function it_fails_if_endpoint_already_exists_in_asterisk_db()
    {
        $idDuplicado = 'tronco-saida';

        // 1. Simulamos que o registro já existe no banco do Asterisk
        DB::connection('asterisk')->table('ps_endpoints')->insert([
            'id' => $idDuplicado,
            'transport' => 'transport-udp',
        ]);

        // 2. Tentamos criar o mesmo ID via Laravel
        // Esperamos que o sistema lance uma exceção de integridade (PDOException)
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        // Se você usa a Model Virtual 'Extension', tente criar uma
        Extension::create([
            'number' => $idDuplicado,
            'password' => 'secret123',
            'tenant_id' => 1
        ]);
    }
}
