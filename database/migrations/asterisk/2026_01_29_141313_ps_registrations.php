<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ps_registrations', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->integer('tenant_id')->nullable()->index();
            $table->string('server_uri', 200)->index();
            $table->string('client_uri', 200)->index();
            $table->string('contact_user', 40)->nullable();
            $table->string('auth_username', 40)->nullable()->index();
            $table->string('password', 191)->nullable();
            $table->string('outbound_proxy', 200)->nullable();
            $table->integer('expiration')->default(3600);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ps_registrations');
    }
};