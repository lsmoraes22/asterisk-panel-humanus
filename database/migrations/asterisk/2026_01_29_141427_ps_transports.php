<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ps_transports', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->integer('tenant_id')->nullable()->index();
            $table->string('type', 20)->default('transport-udp');
            $table->string('bind', 40)->nullable()->default('0.0.0.0:5060');
            $table->string('local_net', 50)->nullable();
            $table->string('external_media_address', 50)->nullable();
            $table->string('external_signaling_address', 50)->nullable();
            $table->string('outbound_proxy', 100)->nullable();
            $table->string('protocol', 10)->default('udp')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ps_transports');
    }
};
