<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('queues', function (Blueprint $table) {

            $table->integer('retry')->default(5);
            $table->integer('wrapuptime')->default(0);
            $table->integer('maxlen')->default(0);
            $table->integer('announce_frequency')->default(0);
            $table->boolean('announce_holdtime')->default(1);
            $table->boolean('announce_position')->default(1);
            $table->boolean('joinempty')->default(1);
            $table->boolean('leavewhenempty')->default(0);
            $table->boolean('ringinuse')->default(0);
            $table->boolean('timeoutrestart')->default(1);
            $table->integer('weight')->default(0);
            $table->boolean('setinterfacevar')->default(1);
            $table->boolean('setqueuevar')->default(1);
            $table->boolean('setqueueentryvar')->default(1);
            $table->boolean('reportholdtime')->default(0);
            $table->boolean('announce_override')->nullable();
            $table->integer('announce_round_seconds')->default(0);
            $table->string('context')->nullable();
            $table->string('monitor_format')->nullable();
            $table->integer('memberdelay')->default(0);
            $table->boolean('autopause')->default(0);
            $table->integer('autopausedelay')->default(0);
            $table->boolean('autopausebusy')->default(0);
            $table->boolean('autopauseunavail')->default(0);
            $table->integer('penaltymemberslimit')->default(0);
            $table->integer('penaltytimeout')->default(0);
            $table->integer('penaltytimerepeat')->default(0);
	        $table->bigInteger('queue_rule_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {

            $table->dropColumn([
                'retry',
                'wrapuptime',
                'maxlen',
                'announce_frequency',
                'announce_holdtime',
                'announce_position',
                'joinempty',
                'leavewhenempty',
                'ringinuse',
                'timeoutrestart',
                'weight',
                'setinterfacevar',
                'setqueuevar',
                'setqueueentryvar',
                'reportholdtime',
                'announce_override',
                'announce_round_seconds',
                'context',
                'monitor_format',
                'memberdelay',
                'autopause',
                'autopausedelay',
                'autopausebusy',
                'autopauseunavail',
                'penaltymemberslimit',
                'penaltytimeout',
                'penaltytimerepeat',
        		'queue_rule_id'
            ]);
        });
    }
};
