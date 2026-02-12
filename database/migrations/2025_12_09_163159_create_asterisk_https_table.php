<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asterisk_http', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');

            $table->boolean('enabled')->default(1);
            $table->string('bindaddr')->nullable();
            $table->integer('bindport')->nullable();
            $table->string('prefix')->nullable();
            $table->boolean('sessioncookies')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asterisk_http');
    }
};
