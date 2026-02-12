<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('sip_peers', function (Blueprint $table) {

            $table->string('name', 80)->primary();

            $table->integer('tenant_id')->nullable()->index();

            $table->string('fullname', 80)->nullable();

            $table->string('host', 50)->nullable()->default('dynamic');
            $table->string('type', 20)->nullable()->default('peer');

            $table->string('context', 50)->nullable()->index();

            $table->string('defaultuser', 80)->nullable();
            $table->string('secret', 80)->nullable();

            $table->string('qualify', 20)->nullable()->default('yes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sip_peers');
    }
};
