<?php

namespace App\Listeners;

use App\Events\confirmReservationEvent;
use App\Events\updateReservationEvent;
use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class confirmReservationListener
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
    public function handle(confirmReservationEvent $event): string
    {
        $reservationUpdate = Reservation::findOrFail($event->reservationId);

            $reservationUpdate->confirmed = 1;
            $reservationUpdate->confirmation_date = now();
            $reservationUpdate->save();
      
            return "llego";

        event(new updateReservationEvent($event->reservationId));

        //TODO: Enviar correo
    }
}
