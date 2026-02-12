<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id()->comment('Identificador interno do tenant');

            $table->string('code', 20)
                ->unique()
                ->comment('Código único sequencial do tenant, ex: tenant-0001');

            $table->string('name', 255)
                ->comment('Nome comercial do cliente/tenant');

            $table->string('domain', 255)
                ->nullable()
                ->comment('Domínio SIP público deste tenant');

            $table->string('external_signaling_address', 255)
                ->nullable()
                ->comment('Endereço externo usado para sinalização SIP (externo)');

            $table->string('external_media_address', 255)
                ->nullable()
                ->comment('Endereço externo usado para tráfego RTP/mídia');

            $table->string('local_net', 255)
                ->nullable()
                ->comment('Rede local interna do tenant, ex: 192.168.0.0/24');

            $table->integer('max_endpoints')
                ->default(20)
                ->comment('Limite de ramais/endpoints do tenant');

            $table->integer('max_queues')
                ->default(5)
                ->comment('Limite de filas/queues do tenant');

            $table->integer('max_channels')
                ->default(50)
                ->comment('Limite simultâneo de canais para o tenant');

            $table->string('timezone', 64)
                ->default('America/Sao_Paulo')
                ->comment('Fuso horário do tenant');

            $table->boolean('active')
                ->default(true)
                ->comment('Status do tenant: ativo ou inativo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
  //      Schema::table('tenants', function (Blueprint $table) {
  //          // Remover chaves estrangeiras antes de dropar a tabela
  //          $table->dropForeign(['tenant_id']);
  //      });
        Schema::dropIfExists('tenants');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
