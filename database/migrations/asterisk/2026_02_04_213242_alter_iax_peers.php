<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ... (mantenha os códigos anteriores de voicemail, queue_log e queue_members)

        // Ajuste na iax_peers (Evita avisos/erros no boot)
        if (Schema::connection($this->connection)->hasTable('iax_peers')) {
            Schema::connection($this->connection)->table('iax_peers', function (Blueprint $table) {
                if (!Schema::connection($this->connection)->hasColumn('iax_peers', 'ipaddr')) {
                    $table->string('ipaddr', 45)->nullable();
                }
                if (!Schema::connection($this->connection)->hasColumn('iax_peers', 'port')) {
                    $table->integer('port')->nullable();
                }
                if (!Schema::connection($this->connection)->hasColumn('iax_peers', 'regseconds')) {
                    $table->integer('regseconds')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        // ... (reversão dos anteriores)

        if (Schema::connection($this->connection)->hasTable('iax_peers')) {
            Schema::connection($this->connection)->table('iax_peers', function (Blueprint $table) {
                $table->dropColumn(['ipaddr', 'port', 'regseconds']);
            });
        }
    }
};
