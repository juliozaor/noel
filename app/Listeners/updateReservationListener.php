<?php

namespace App\Listeners;

use App\Events\updateProgrammingEvent;
use App\Events\updateReservationEvent;
use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class updateReservationListener
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
    public function handle(updateReservationEvent $event): void
    {
        
        $reservationUpdate = Reservation::findOrFail($event->reservationId);

            $membersCount = $reservationUpdate->member()->count();
            $reservationUpdate->quota = $membersCount+1;
            $reservationUpdate->save();

            event(new updateProgrammingEvent($reservationUpdate->programming_id));
       
    }
}
