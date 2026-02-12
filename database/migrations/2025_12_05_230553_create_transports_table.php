<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
	Schema::create('transports', function (Blueprint $table) {
	    $table->id()->comment('ID do transporte SIP');
	    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete()->comment('Tenant dono do transporte');
	    $table->string('name')->comment('Nome do transporte, ex: transport-udp');
	    $table->string('protocol')->default('udp')->comment('Protocolo usado pelo transporte');
	    $table->string('bind')->default('0.0.0.0')->comment('Endereço IP de bind');
	    $table->timestamps();
	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
        });
        Schema::dropIfExists('transports');
    }
};
