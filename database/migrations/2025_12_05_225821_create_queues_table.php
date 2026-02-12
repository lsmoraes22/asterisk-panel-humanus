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
        Schema::create('queues', function (Blueprint $table) {
	    $table->id()->comment('ID da fila');
	    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete()->comment('Tenant dono da fila');
	    $table->string('name')->comment('Nome da fila');
	    $table->string('strategy')->default('ringall')->comment('Estratégia de chamada da fila');
	    $table->integer('timeout')->default(15)->comment('Timeout da chamada em segundos');
	    $table->string('musicclass')->default('default')->comment('Classe de música em espera');
	    $table->timestamps();
	});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Desativa a verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::table('queues', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
        });
        Schema::dropIfExists('queues');
            // Reativa a verificação
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
