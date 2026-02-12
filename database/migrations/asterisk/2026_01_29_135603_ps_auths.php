<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'asterisk';

    public function up(): void
    {
        Schema::create('ps_auths', function (Blueprint $table) {
            $table->string('id', 40)->primary();

            $table->integer('tenant_id')->nullable()->index();
            $table->string('auth_type', 20)->default('userpass');
            $table->string('username', 40)->nullable()->index();
            $table->string('password', 191)->nullable();
            $table->string('realm', 40)->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ps_auths');
    }
};
