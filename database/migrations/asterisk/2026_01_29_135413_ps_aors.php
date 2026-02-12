<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'asterisk';

    public function up(): void
    {
        Schema::create('ps_aors', function (Blueprint $table) {
            $table->string('id', 40)->primary();

            $table->integer('tenant_id')->nullable()->index();
            $table->integer('max_contacts')->nullable()->default(1)->index();
            $table->string('remove_existing', 10)->nullable()->default('yes');
            $table->integer('minimum_expiration')->nullable()->default(60);
            $table->integer('qualify_frequency')->nullable()->default(60);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ps_aors');
    }
};
