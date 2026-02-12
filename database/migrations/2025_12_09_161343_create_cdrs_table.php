<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cdrs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');

            $table->string('calldate')->nullable();
            $table->string('clid')->nullable();
            $table->string('src')->nullable();
            $table->string('dst')->nullable();
            $table->string('dcontext')->nullable();
            $table->string('channel')->nullable();
            $table->string('dstchannel')->nullable();
            $table->string('lastapp')->nullable();
            $table->string('lastdata')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('billsec')->nullable();
            $table->string('disposition')->nullable();
            $table->string('amaflags')->nullable();
            $table->string('accountcode')->nullable();
            $table->string('uniqueid')->nullable();
            $table->string('userfield')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cdrs');
    }
};

