<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('rtp_stats', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->integer('tenant_id')->nullable()->index();

            $table->dateTime('starttime')->index();
            $table->dateTime('endtime')->nullable()->index();

            $table->string('callid', 80)->nullable()->index();

            $table->string('source_ip', 45)->nullable()->index();
            $table->integer('source_port')->nullable();

            $table->string('dest_ip', 45)->nullable()->index();
            $table->integer('dest_port')->nullable();

            $table->integer('packets_sent')->nullable()->default(0);
            $table->integer('packets_received')->nullable()->default(0);

            $table->bigInteger('bytes_sent')->nullable()->default(0);
            $table->bigInteger('bytes_received')->nullable()->default(0);

            $table->integer('packet_loss')->nullable()->default(0);
            $table->float('jitter')->nullable()->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rtp_stats');
    }
};
