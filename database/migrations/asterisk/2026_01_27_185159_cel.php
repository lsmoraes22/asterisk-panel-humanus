<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'asterisk';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cel', function (Blueprint $table) {
            $table->dateTime('eventtime');
            $table->integer('tenant_id')->nullable()->index();
            $table->string('eventtype', 50);
            $table->string('cid_name', 80)->nullable();
            $table->string('cid_num', 80)->nullable()->index();
            $table->string('src', 80)->nullable();
            $table->string('dst', 80)->nullable()->index();
            $table->string('dcontext', 80)->nullable();
            $table->string('channel', 80)->nullable();
            $table->string('dstchannel', 80)->nullable();
            $table->string('lastapp', 80)->nullable();
            $table->string('lastdata', 80)->nullable();
            $table->integer('amaflags')->nullable();
            $table->string('accountcode', 20)->nullable();
            $table->string('uniqueid', 32);
            $table->string('userfield', 255)->nullable();
            $table->string('peeraccount', 20)->nullable();
            $table->string('linkedid', 32)->nullable();

            $table->primary(['eventtime', 'uniqueid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cel');
    }
};
