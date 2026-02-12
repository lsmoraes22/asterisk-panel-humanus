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
        Schema::create('agents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->nullable()->index();
            $table->string('name', 80)->unique();
            $table->string('password', 191);
            $table->string('fullname', 80)->nullable();
            $table->string('status', 20)->nullable();
            $table->dateTime('lastlogin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
