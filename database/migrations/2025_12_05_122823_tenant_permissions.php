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
        Schema::create('tenant_permissions', function (Blueprint $table) {
	    $table->id();
	    $table->unsignedBigInteger('tenant_id')->comment('Tenant ao qual esta permissão pertence');
	    $table->string('permission', 100)->comment('Código da permissão');
	    $table->string('description')->nullable()->comment('Descrição da permissão');
	    $table->timestamps();

	    $table->unique(['tenant_id', 'permission']);
	    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_permissions', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
        });
        Schema::dropIfExists('tenant_permissions');
    }
};

