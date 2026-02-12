<?php

namespace App\Observers;

use App\Models\PjsipAuth;
use Illuminate\Support\Facades\DB;

class PjsipAuthObserver
{
    /**
     * Handle the PjsipAuth "created" event.
     */
    public function created(PjsipAuth $pjsipAuth): void
    {
        DB::connection('asterisk')->table('ps_auths')->insert([
            'id' => $pjsipAuth->id,
            'auth_type' => $pjsipAuth->auth_type,
            'password' => $pjsipAuth->password,
            'username' => $pjsipAuth->username,
        ]);
    }

    /**
     * Handle the PjsipAuth "updated" event.
     */
    public function updated(PjsipAuth $pjsipAuth): void
    {
        DB::connection('asterisk')->table('ps_auths')->where('id', $pjsipAuth->id)->update([
            'auth_type' => $pjsipAuth->auth_type,
            'password' => $pjsipAuth->password,
            'username' => $pjsipAuth->username,
        ]);
    }

    /**
     * Handle the PjsipAuth "deleted" event.
     */
    public function deleted(PjsipAuth $pjsipAuth): void
    {
        DB::connection('asterisk')->table('ps_auths')->where('id', $pjsipAuth->id)->delete();
    }

    /**
     * Handle the PjsipAuth "restored" event.
     */
    public function restored(PjsipAuth $pjsipAuth): void
    {
        //
    }

    /**
     * Handle the PjsipAuth "force deleted" event.
     */
    public function forceDeleted(PjsipAuth $pjsipAuth): void
    {
        DB::connection('asterisk')->table('ps_auths')->where('id', $pjsipAuth->id)->delete();
    }
}
