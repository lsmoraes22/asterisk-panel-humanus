<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('asterisk')->create('ast_manager_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 80)->unique();
            $table->string('secret', 80);
            $table->string('deny', 255)->nullable();
            $table->string('permit', 255)->nullable();
            $table->string('read', 255)->nullable();
            $table->string('write', 255)->nullable();
            $table->integer('writetimeout')->nullable()->default(100);
            $table->string('displayconnects', 3)->nullable()->default('yes');
        });
    }

    public function down(): void
    {
        Schema::connection('asterisk')->dropIfExists('ast_manager_users');
    }
};
