<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('queue_rules', function (Blueprint $table) {

            $table->string('rule_name', 80);
            $table->integer('tenant_id')->nullable()->index();

            $table->integer('time');
            $table->string('agent', 40);

            $table->integer('count')->nullable()->default(0);
            $table->integer('max_wait_time')->nullable()->default(0);

            // Primary Key composta (como no Asterisk)
            $table->primary(['rule_name', 'time', 'agent']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_rules');
    }
};
