<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'asterisk';

    public function up(): void
    {
        Schema::create('ps_endpoints', function (Blueprint $table) {
            $table->string('id', 40)->primary();

            $table->integer('tenant_id')->nullable()->index();

            $table->string('transport', 40)->nullable()->index();
            $table->string('aors', 200)->nullable()->index();
            $table->string('auth', 40)->nullable()->index();

            $table->string('mailboxes')->nullable();

            $table->string('context', 40)->nullable()->default('default');

            $table->string('disallow', 200)->nullable()->default('all');
            $table->string('allow', 200)->nullable()->default('ulaw,alaw');

            $table->string('direct_media', 10)->nullable()->default('no');
            $table->string('dtmf_mode', 20)->nullable()->default('rfc4733');

            $table->string('rtp_symmetric', 10)->nullable()->default('yes');
            $table->string('force_rport', 10)->nullable()->default('yes');
            $table->string('rewrite_contact', 10)->nullable()->default('yes');

            $table->string('callerid', 80)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ps_endpoints');
    }
};