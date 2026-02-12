<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'asterisk';

    public function up(): void
    {
        Schema::create('iax_users', function (Blueprint $table) {
            $table->string('name', 80)->primary();

            $table->integer('tenant_id')->nullable()->index();
            $table->string('fullname', 80)->nullable();
            $table->string('defaultuser', 80)->nullable();
            $table->string('secret', 80)->nullable();
            $table->string('context', 50)->nullable()->index();
            $table->string('host', 50)->nullable()->default('dynamic');
            $table->string('type', 20)->nullable()->default('friend');
            $table->string('allow', 255)->nullable();
            $table->string('nat', 20)->nullable()->default('yes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iax_users');
    }
};
