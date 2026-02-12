<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'asterisk'; // Garante que rode no banco do Asterisk
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->create('extensions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('context', 40);
            $table->string('exten', 40);
            $table->integer('priority')->default(1);
            $table->string('app', 40);
            $table->string('appdata', 256)->default('');
            
            // Chave única para evitar que o Asterisk se perca nas prioridades
            $table->unique(['context', 'exten', 'priority'], 'context_exten_priority');
            $table->index(['context', 'exten']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('extensions');
    }
};
