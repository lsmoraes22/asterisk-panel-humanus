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
	Schema::create('features', function (Blueprint $table) {
	    $table->id()->comment('ID da feature');
	    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete()->comment('Tenant dono da feature');
	    $table->string('name')->comment('Nome da feature, ex: callparking');
	    $table->string('code')->comment('Código ou tecla para ativar a feature, ex: *70');
	    $table->boolean('enabled')->default(true)->comment('Se a feature está habilitada');
	    $table->timestamps();
	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('features', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
        });
        Schema::dropIfExists('features');
    }
};
