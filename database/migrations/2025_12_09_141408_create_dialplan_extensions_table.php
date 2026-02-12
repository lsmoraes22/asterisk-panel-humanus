<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dialplan_extensions', function (Blueprint $table) {

            $table->id();

            // Quem é o tenant dono do Dialplan
            $table->foreignId('tenant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Tipo da rota: internal, queue
            $table->enum('type', ['internal', 'queue']);

            // Número discado (ex: 1001, 8000)
            $table->string('extension');

            // Referência ao endpoint, quando type = internal
            $table->string('endpoint_id')->nullable(); 
                
            // Define a restrição manualmente
            $table->foreign('endpoint_id')
                ->references('id')
                ->on('pjsip_endpoints')
                ->onDelete('set null');

            // Referência à fila, quando type = queue
            $table->foreignId('queue_id')->nullable()->constrained()->onDelete('set null');

            // Timeout, voicemail, etc (pode expandir depois)
            $table->integer('timeout')->default(20);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('dialplan_extensions', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
            $table->dropForeign(['endpoint_id']);
            $table->dropForeign(['queue_id']);
        });
        Schema::dropIfExists('dialplan_extensions');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
