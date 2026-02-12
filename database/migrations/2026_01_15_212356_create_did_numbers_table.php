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
    	Schema::create('did_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            // O número real (ex: 551140041234)
            $table->string('number')->unique();

            // Descrição amigável (ex: Tronco Principal)
            $table->string('description')->nullable();

            // Destino dentro do Dialplan (ex: 100, 5000, uramain)
            $table->string('destination');

            // Tipo de destino (opcional, ajuda o Filament a renderizar selects)
            $table->enum('destination_type', ['endpoint', 'queue', 'ivr', 'custom'])->default('endpoint');

            $table->boolean('active')->default(true);
            $table->timestamps();
    	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('did_numbers');
    }
};
