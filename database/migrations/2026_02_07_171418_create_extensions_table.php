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
        Schema::create('extensions', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // Ex: 1001
            $table->string('password');
            $table->string('display_name')->nullable(); // Nome do Usuário (ex: João)
            $table->foreignId('tenant_id')->constrained();
            $table->string('context')->default('from-internal');
            $table->string('voicemail')->nullable(); // Ex: 1001@default
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extensions');
    }
};
