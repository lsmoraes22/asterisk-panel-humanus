<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;

class InviteController extends Controller
{
    public function activate($token) {
        $invite = Invite::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $ip = request()->ip();

        // Adiciona ao IPSET (usando /32 para IP único)
        shell_exec("sudo ipset add ufw_allowed_ips {$ip} -exist");

        $invite->update(['ip_address' => $ip]);

        return "Acesso liberado para o IP {$ip}! Você já pode configurar seu ramal.";
    }
}
