<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('asterisk')->create('voicemail', function (Blueprint $table) {

            $table->string('mailbox', 50);
            $table->integer('tenant_id')->nullable()->index();
            $table->string('context', 50);
            $table->string('password', 50);
            $table->string('fullname', 80)->nullable();
            $table->string('email', 80)->nullable()->index();
            $table->string('pager', 50)->nullable();
            $table->boolean('attach')->nullable()->default(0);
            $table->boolean('saycid')->nullable()->default(0);
            $table->string('dialout', 255)->nullable();
            $table->string('options', 255)->nullable();

            // PK composta (padrão Asterisk)
            $table->primary(['mailbox', 'context']);
        });
    }

    public function down(): void
    {
        Schema::connection('asterisk')->dropIfExists('voicemail');
    }
};
