<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('queue_members', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('tenant_id')->nullable()->index();

            $table->string('queue_name', 80)->index();
            $table->string('interface', 80)->index();

            $table->string('membername', 80)->nullable();

            $table->integer('penalty')->nullable()->default(0);
            $table->boolean('paused')->nullable()->default(false);

            $table->string('laststatus', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_members');
    }
};
