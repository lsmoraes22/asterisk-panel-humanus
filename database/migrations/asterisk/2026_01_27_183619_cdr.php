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
        Schema::create('cdr', function (Blueprint $table) {
            $table->dateTime('calldate');
            $table->integer('tenant_id')->nullable()->index();
            $table->string('src', 80)->nullable()->index();
            $table->string('dst', 80)->nullable()->index();
            $table->string('uniqueid', 32)->primary();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cdr');
    }
};
