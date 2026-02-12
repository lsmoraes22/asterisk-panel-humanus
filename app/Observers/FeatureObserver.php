<?php

namespace App\Observers;

class FeatureObserver
{
    /*
    protected $confPath = '/etc/asterisk/features.conf';

    public function saved(Feature $feature)
    {
        $features = \App\Models\Feature::all();
        $content = "[general]\n\n[featuremap]\n";

        foreach ($features as $f) {
            // Ex: blindxfer => *1
            $content .= "{$f->name} => {$f->code}\n";
        }

        File::put($this->confPath, $content);
        shell_exec('asterisk -rx "features reload"');
    }
        /**/
}
