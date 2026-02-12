<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queue_rules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->onDelete('cascade');

            $table->string('name')->unique(); // nome da regra, ex: regra_suporte

            $table->json('steps')->nullable(); 
            // Exemplo:
            // [
            //   { "announce_frequency": 15, "timeout": 30, "retry": 5 },
            //   { "announce_frequency": 10, "timeout": 25, "retry": 3 }
            // ]

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('queue_rules', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
        });
        Schema::dropIfExists('queue_rules');
    }
};
