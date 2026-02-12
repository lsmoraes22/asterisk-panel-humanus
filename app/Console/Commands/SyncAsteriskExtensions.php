<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncAsteriskExtensions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-asterisk-extensions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando sincronização com Asterisk...');

        // 1. Pega todos os IDs de endpoints no banco do Asterisk
        $asteriskIds = DB::connection('asterisk')->table('ps_endpoints')->pluck('id');

        // 2. Pega todos os números já cadastrados no Laravel
        $laravelNumbers = \App\Models\Extension::pluck('number')->toArray();

        foreach ($asteriskIds as $id) {
            if (!in_array($id, $laravelNumbers)) {
                $this->warn("Ramal fanyasma detectado: {$id}. Importando...");

                // Busca dados complementares no ps_auths para pegar a senha
                $auth = DB::connection('asterisk')->table('ps_auths')->where('id', $id)->first();

                \App\Models\Extension::create([
                    'number' => $id,
                    'password' => $auth ? $auth->password : 'alterar123',
                    'display_name' => 'Importado do Asterisk',
                    'tenant_id' => 1, // Ou uma lógica para detectar o tenant pelo contexto
                    'context' => 'from-internal',
                ]);
            }
        }

        $this->info('Sincronização concluída!');
    }
}
