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
	Schema::create('voicemail_boxes', function (Blueprint $table) {
	    $table->id()->comment('ID da caixa de correio');
	    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete()->comment('Tenant dono da caixa');
	    $table->string('mailbox')->comment('Número da caixa de correio');
	    $table->string('password')->comment('Senha da caixa de correio');
	    $table->string('name')->nullable()->comment('Nome do usuário');
	    $table->string('email')->nullable()->comment('E-mail para notificações');
	    $table->timestamps();
	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voicemail_boxes', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
        });
        Schema::dropIfExists('voicemail_boxes');
    }
};
