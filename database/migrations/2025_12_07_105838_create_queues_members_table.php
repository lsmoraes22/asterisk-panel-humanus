<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queue_members', function (Blueprint $table) {
            $table->id();
            // Relação com Tenant (multi-tenant)
            $table->foreignId('tenant_id')
                  ->constrained('tenants')
                  ->onDelete('cascade');

            // Cada membro pertence a uma queue
            $table->foreignId('queue_id')
                  ->constrained('queues')
                  ->onDelete('cascade');

            // Qual endpoint está sendo associado
            $table->biginteger('endpoint_id');

            // Campos avançados de Queue Member (Asterisk)
            $table->integer('penalty')->default(0);
            $table->string('state_interface')->nullable(); // Ex.: PJSIP/1001
            $table->enum('paused', ['yes', 'no'])->default('no');
            $table->string('membername')->nullable();

            // Caso queira log de auditoria interno
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('queue_members', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
            $table->dropForeign(['queue_id']);
        });
        Schema::dropIfExists('queue_members');
    }
};
