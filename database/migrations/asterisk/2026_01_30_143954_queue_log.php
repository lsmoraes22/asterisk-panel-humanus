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
        Schema::connection($this->connection)->create('queue_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tenant_id')->nullable()->index();
            $table->datetime('time')->index();
            $table->string('callid', 80)->index();
            $table->string('queuename', 80)->index();
            $table->string('agent', 80)->nullable()->index();
            $table->string('event', 50);
            $table->string('data1', 80)->nullable();
            $table->string('data2', 80)->nullable();
            $table->string('data3', 80)->nullable();
            $table->string('data4', 80)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('queue_log');
    }
};
