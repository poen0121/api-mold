<?php

namespace App\Libraries\Upgrades;

use Spatie\Activitylog\ActivityLogger;

class BetterActivityLogger extends ActivityLogger
{

    public function log(string $description)
    {
        if ($this->logStatus->disabled()) {
            return;
        }

        if (! in_array($this->activity->log_name, config('activitylog.available_log_name', [])) || in_array($this->activity->log_name, config('activitylog.ignore_log_name', []))) {
            return;
        }
        
        if ($description == '' && ! isset($activity->description[0])) {
            return;
        }

        $activity = $this->activity;

        $activity->description = $this->replacePlaceholders(
            $activity->description ?? $description,
            $activity
        );

        $activity->save();

        $this->activity = null;

        return $activity;
    }
}
