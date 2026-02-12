<?php

namespace App\Observers;

class ManagerUserObserver
{
    /*
    protected $confPath = '/etc/asterisk/manager.conf';

    public function saved(ManagerUser $user)
    {
        $this->generateConfig();
    }

    public function deleted(ManagerUser $user)
    {
        $this->generateConfig();
    }

    private function generateConfig()
    {
        $users = \App\Models\ManagerUser::all();
        $content = "[general]\nenabled = yes\nport = 5038\nbindaddr = 0.0.0.0\n\n";

        foreach ($users as $u) {
            $content .= "[{$u->username}]\n";
            $content .= "secret = {$u->password}\n";
            $content .= "read = system,call,log,verbose,agent,user,config,dtmf,reporting,cdr,dialplan\n";
            $content .= "write = system,call,agent,user,config,command,reporting,originate\n";
            $content .= "permit = 127.0.0.1/255.255.255.255\n\n";
        }

        File::put($this->confPath, $content);
        shell_exec('asterisk -rx "manager reload"');
    }
    /**/
}
