<?php

namespace App\Observers;

use App\Models\PjsipAor;
use Illuminate\Support\Facades\DB;

class PjsipAorObserver
{
    /**
     * Handle the PjsipAor "created" event.
     */
    public function created(PjsipAor $pjsipAor): void
    {
        DB::connection('asterisk')->table('ps_aors')->insert([
            'id' => $pjsipAor->id,
            'max_contacts' => $pjsipAor->max_contacts ?? 1,
            'remove_existing' => 'yes',
        ]);
    }

    /**
     * Handle the PjsipAor "updated" event.
     */
    public function updated(PjsipAor $pjsipAor): void
    {
        DB::connection('asterisk')->table('ps_aors')->where('id', $pjsipAor->id)->update([
            'max_contacts' => $pjsipAor->max_contacts ?? 1,
            'remove_existing' => 'yes',
            'username' => $pjsipAor->name,
        ]);
    }

    /**
     * Handle the PjsipAor "deleted" event.
     */
    public function deleted(PjsipAor $pjsipAor): void
    {
        DB::connection('asterisk')->table('ps_aors')->where('id', $pjsipAor->id)->delete();
    }

    /**
     * Handle the PjsipAor "restored" event.
     */
    public function restored(PjsipAor $pjsipAor): void
    {

    }

    /**
     * Handle the PjsipAor "force deleted" event.
     */
    public function forceDeleted(PjsipAor $pjsipAor): void
    {
        DB::connection('asterisk')->table('ps_aors')->where('id', $pjsipAor->id)->delete();
    }
}
