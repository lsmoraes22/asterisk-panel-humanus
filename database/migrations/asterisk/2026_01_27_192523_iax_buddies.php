<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'asterisk';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('iax_buddies', function (Blueprint $table) {
            $table->string('name', 80)->primary();

            $table->integer('tenant_id')->nullable()->index();
            $table->string('fullname', 80)->nullable();
            $table->string('host', 50)->nullable();
            $table->integer('port')->nullable()->default(4569);
            $table->string('type', 20)->nullable();
            $table->string('context', 50)->nullable()->index();
            $table->string('defaultuser', 80)->nullable();
            $table->string('secret', 80)->nullable();
            $table->integer('regseconds')->nullable();
            $table->string('ipaddr', 50)->nullable()->index();
            $table->string('status', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iax_buddies');
    }
};
