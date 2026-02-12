<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pjsip_endpoints', function (Blueprint $table) {
            $table->string('id')->primary()->comment('ID do endpoint');
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete()->comment('Tenant ao qual o endpoint pertence');
            $table->string('name')->comment('Nome do endpoint');
            $table->string('auth')->nullable()->comment('Nome do auth associado');
            $table->string('mailboxes')->nullable()->comment('Mailboxes associadas, separadas por vírgula');
            $table->string('aor')->nullable()->comment('Nome do AOR associado');
            $table->string('context')->default('from-internal')->comment('Contexto do Dialplan');
            $table->string('transport')->default('udp')->comment('Transporte SIP usado');
            $table->string('allow')->default('ulaw,alaw')->comment('Codecs permitidos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pjsip_endpoints', function (Blueprint $table) {
            // Remover chaves estrangeiras antes de dropar a tabela
            $table->dropForeign(['tenant_id']);
        });
        Schema::dropIfExists('pjsip_endpoints');
    }
};
