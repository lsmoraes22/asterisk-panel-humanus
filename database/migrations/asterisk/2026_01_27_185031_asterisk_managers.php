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
        Schema::create('asterisk_managers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 80)->unique();
            $table->string('secret', 80);
            $table->string('deny', 255)->nullable();
            $table->string('permit', 255)->nullable();
            $table->string('read', 255)->nullable();
            $table->string('write', 255)->nullable();
            $table->integer('writetimeout')->default(100);
            $table->string('displayconnects', 3)->default('yes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asterisk_managers');
    }
};
