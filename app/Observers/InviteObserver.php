<?php

namespace App\Observers;

class InviteObserver
{
    public function deleted(Invite $invite)
    {
        if ($invite->ip_address) {
            // Remove o IP do conjunto ufw_allowed_ips ao deletar o registro
            shell_exec("sudo ipset del ufw_allowed_ips {$invite->ip_address}");
        }
    }
}
