<?php

namespace App\Observers;

use App\Models\AsteriskHttp;
use Illuminate\Support\Facades\File;

class AsteriskHttpObserver
{
    /*
    protected $confPath = '/etc/asterisk/http.conf';

    public function updated(AsteriskHttp $http)
    {
        $this->generateConfig($http);
    }

    public function created(AsteriskHttp $http)
    {
        $this->generateConfig($http);
    }

    private function generateConfig(AsteriskHttp $http)
    {
        $enabled = $http->enabled ? 'yes' : 'no';
        
        // Monta o conteúdo do arquivo http.conf
        $content = "[general]\n";
        $content .= "enabled={$enabled}\n";
        $content .= "bindaddr={$http->bind_address}\n";
        $content .= "bindport={$http->port}\n";
        
        if ($http->enablestatic) {
            $content .= "enablestatic=yes\n";
        }

        // Escreve no arquivo (Laravel precisa de permissão sudo ou o diretório ser do grupo www-data)
        File::put($this->confPath, $content);

        // Avisa o Asterisk para recarregar as configurações de HTTP
        // Se você tiver um serviço de AMI, dispare o comando 'http reload' aqui.
        $this->reloadHttp();
    }

    private function reloadHttp()
    {
        shell_exec('asterisk -rx "http reload"');
    }
    /**/
}