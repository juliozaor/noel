<?php

namespace App\Listeners;

use App\Events\updateProgrammingEvent;
use App\Models\Programming;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class updateProgrammingListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(updateProgrammingEvent $event): void
    {
        if($event->programmingId <> 1){
        $programming = Programming::findOrFail($event->programmingId);
        $totalReservationsQuota = $programming->reservation()->sum('quota');
        $newQuotaAvailable = $programming->quota - $totalReservationsQuota;

        
        $programming->update([
            'quota_available' => $newQuotaAvailable
        ]);
    }
}
}
