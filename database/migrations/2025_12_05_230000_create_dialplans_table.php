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
	Schema::create('dialplans', function (Blueprint $table) {
	    $table->id()->comment('ID da regra de Dialplan');
	    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete()->comment('Tenant dono do Dialplan');
	    $table->string('context')->comment('Contexto do Dialplan');
	    $table->string('exten')->comment('Número ou padrão de extensão');
	    $table->integer('priority')->default(1)->comment('Prioridade da instrução');
	    $table->string('application')->comment('Aplicativo a ser chamado, ex: Dial, Answer');
	    $table->string('app_data')->nullable()->comment('Argumentos adicionais para o aplicativo');
	    $table->timestamps();
	});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('dialplans', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
        });
        Schema::dropIfExists('dialplans');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
