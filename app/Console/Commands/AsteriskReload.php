<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AsteriskReload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asterisk:reload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executa o Dialplan reload no Asterisk';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $process = new Process(['asterisk', '-rx', 'Dialplan reload']);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error("Erro ao recarregar Dialplan:");
            $this->error($process->getErrorOutput());
            return;
        }

        $this->info("Dialplan reload executado com sucesso!");
        $this->line($process->getOutput());
    }
}
