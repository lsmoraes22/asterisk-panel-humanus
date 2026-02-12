<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'asterisk';

    public function up(): void
    {

        Schema::connection($this->connection)->table('voicemail', function (Blueprint $table) {
            // Se o campo não existir, adicionamos como uma coluna comum, mas auto-incremento
            if (!Schema::connection($this->connection)->hasColumn('voicemail', 'uniqueid')) {
                // Usamos unsignedBigInteger e adicionamos o auto-incremento e o index manualmente
                // para não tentar sobrescrever a Primary Key composta (mailbox, context)
                $table->unsignedBigInteger('uniqueid')->autoIncrement(false)->nullable()->after('tenant_id');
            }
        });

        // Como o Blueprint do Laravel às vezes força a PK no autoIncrement,
        // usamos o SQL puro para garantir que ele seja apenas um índice único
        DB::connection($this->connection)->statement(
            "ALTER TABLE voicemail MODIFY COLUMN uniqueid BIGINT NOT NULL AUTO_INCREMENT, ADD KEY (uniqueid)"
        );

        // Ajuste na queue_log: Asterisk exige data5 para não crashar no INSERT
        if (Schema::connection($this->connection)->hasTable('queue_log')) {
            Schema::connection($this->connection)->table('queue_log', function (Blueprint $table) {
                if (!Schema::connection($this->connection)->hasColumn('queue_log', 'data5')) {
                    $table->string('data5', 80)->nullable()->after('data4');
                }
            });
        }

        // Ajuste na queue_members
        if (Schema::connection($this->connection)->hasTable('queue_members')) {
            Schema::connection($this->connection)->table('queue_members', function (Blueprint $table) {
                // Se o 'id' existe e o 'uniqueid' ainda não, apenas renomeamos
                if (Schema::connection($this->connection)->hasColumn('queue_members', 'id') && 
                    !Schema::connection($this->connection)->hasColumn('queue_members', 'uniqueid')) {
                    
                    $table->renameColumn('id', 'uniqueid');
                }
                
                // Garantimos que reason_paused e paused estejam corretos
                if (!Schema::connection($this->connection)->hasColumn('queue_members', 'reason_paused')) {
                    $table->string('reason_paused', 80)->nullable()->after('paused');
                }
                $table->string('paused', 10)->default('0')->change();
            });
        }
    }

    public function down(): void
    {
        // 1. Reverter alterações na 'voicemail'
        if (Schema::connection($this->connection)->hasTable('voicemail')) {
            Schema::connection($this->connection)->table('voicemail', function (Blueprint $table) {
                if (Schema::connection($this->connection)->hasColumn('voicemail', 'uniqueid')) {
                    // Removemos o AUTO_INCREMENT e a KEY antes de excluir a coluna
                    // (Opcional, mas boa prática em alguns drivers SQL)
                    $table->dropColumn('uniqueid');
                }
            });
        }

        // 2. Reverter alterações na 'queue_log'
        if (Schema::connection($this->connection)->hasTable('queue_log')) {
            Schema::connection($this->connection)->table('queue_log', function (Blueprint $table) {
                if (Schema::connection($this->connection)->hasColumn('queue_log', 'data5')) {
                    $table->dropColumn('data5');
                }
            });
        }

        // 3. Reverter alterações na 'queue_members'
        if (Schema::connection($this->connection)->hasTable('queue_members')) {
            Schema::connection($this->connection)->table('queue_members', function (Blueprint $table) {
                // Volta o nome de uniqueid para id
                if (Schema::connection($this->connection)->hasColumn('queue_members', 'uniqueid') && 
                    !Schema::connection($this->connection)->hasColumn('queue_members', 'id')) {
                    
                    $table->renameColumn('uniqueid', 'id');
                }

                // Remove a coluna reason_paused
                if (Schema::connection($this->connection)->hasColumn('queue_members', 'reason_paused')) {
                    $table->dropColumn('reason_paused');
                }

                // Nota: O 'change()' para o tipo original de 'paused' 
                // dependeria de qual era o tipo anterior (ex: integer). 
                // Se necessário, adicione o rollback do tipo aqui.
            });
        }
    }
};