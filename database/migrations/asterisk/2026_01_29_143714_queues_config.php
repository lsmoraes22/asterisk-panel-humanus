<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('queues_config', function (Blueprint $table) {

            $table->string('name', 80)->primary();

            $table->integer('tenant_id')->nullable()->index();

            $table->string('strategy', 50)->nullable();

            $table->integer('timeout')->nullable()->default(15);
            $table->integer('retry')->nullable()->default(5);
            $table->integer('wrapup_time')->nullable()->default(0);

            $table->string('musicclass', 80)->nullable();

            $table->string('context', 50)->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues_config');
    }
};
