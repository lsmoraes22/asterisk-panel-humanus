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
	Schema::create('pjsip_auths', function (Blueprint $table) {
	    $table->string('id')->primary()->comment('ID do auth SIP');
	    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete()->comment('Tenant dono do auth');
	    $table->string('name')->comment('Nome do auth');
	    $table->string('type')->default('userpass')->comment('Tipo de autenticação (userpass, digest, etc.)');
	    $table->string('username')->nullable()->comment('Usuário SIP');
	    $table->string('password')->nullable()->comment('Senha SIP');
	    $table->timestamps();
	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pjsip_auths', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
        });
        Schema::dropIfExists('pjsip_auths');
    }
};
