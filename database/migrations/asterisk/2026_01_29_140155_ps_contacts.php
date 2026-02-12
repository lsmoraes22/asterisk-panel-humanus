<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'asterisk';

    public function up(): void
    {
        Schema::create('ps_contacts', function (Blueprint $table) {
            $table->string('id', 80)->primary();

            $table->integer('tenant_id')->nullable()->index();
            $table->string('aor', 40)->index();
            $table->string('uri', 200)->index();
            $table->dateTime('expires')->nullable()->index();
            $table->float('qualify_timeout')->nullable();
            $table->integer('qualify_frequency')->nullable();
            $table->string('user_agent', 200)->nullable();
            $table->string('reg_server', 20)->nullable();
            $table->string('via_addr', 40)->nullable();
            $table->string('call_id', 255)->nullable();
            $table->string('endpoint', 40)->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ps_contacts');
    }
};
