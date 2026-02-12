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
	Schema::create('pjsip_aors', function (Blueprint $table) {
	    $table->string('id')->primary()->comment('ID do AOR');
	    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete()->comment('Tenant dono do AOR');
	    $table->string('name')->comment('Nome do AOR');
	    $table->integer('max_contacts')->default(1)->comment('Número máximo de contatos permitidos');
	    $table->timestamps();
	});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pjsip_aors', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
        });
        Schema::dropIfExists('pjsip_aors');
    }
};
