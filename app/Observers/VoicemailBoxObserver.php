<?php

namespace App\Observers;

use App\Models\VoicemailBox;
use Illuminate\Support\Facades\DB;

class VoicemailBoxObserver
{
    /**
     * Ao criar uma nova caixa postal.
     */
    public function created(VoicemailBox $voicemail): void
    {
        DB::connection('asterisk')->table('voicemail')->insert([
            'uniqueid' => $voicemail->id,
            'context'  => $voicemail->context ?? 'default',
            'mailbox'  => $voicemail->mailbox, // Geralmente o número do ramal
            'password' => $voicemail->password ?? '1234', // PIN numérico
            'fullname' => $voicemail->fullname,
            'email'    => $voicemail->email,
            'pager'    => $voicemail->pager,
            'attach'   => 'yes', // Envia o áudio em anexo no e-mail
            'sayduration' => 'yes',
            'envelope' => 'no',
            'deletevoicemail'   => 'no', // Se 'yes', apaga do Asterisk após enviar por e-mail
        ]);

        $this->reloadVoicemail();
    }

    /**
     * Ao atualizar dados (troca de senha ou e-mail).
     */
    public function updated(VoicemailBox $voicemail): void
    {
        DB::connection('asterisk')->table('voicemail')
            ->where('mailbox', $voicemail->getOriginal('mailbox'))
            ->where('context', $voicemail->getOriginal('context'))
            ->update([
                'password' => $voicemail->password,
                'fullname' => $voicemail->fullname,
                'email'    => $voicemail->email,
            ]);

        $this->reloadVoicemail();
    }

    /**
     * Ao deletar a caixa postal.
     */
    public function deleted(VoicemailBox $voicemail): void
    {
        DB::connection('asterisk')->table('voicemail')
            ->where('mailbox', $voicemail->mailbox)
            ->where('context', $voicemail->context)
            ->delete();

        $this->reloadVoicemail();
    }

    /**
     * Aplica as mudanças no motor do Asterisk.
     */
    private function reloadVoicemail(): void
    {
        // O comando 'voicemail reload' recarrega o módulo sem derrubar chamadas.
        shell_exec("asterisk -rx 'voicemail reload'");
    }
}