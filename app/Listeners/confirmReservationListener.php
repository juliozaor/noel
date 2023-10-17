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
    public function handle(confirmReservationEvent $event): void
    {
        $reservationUpdate = Reservation::findOrFail($event->reservationId);

        if ($reservationUpdate) {
            $reservationUpdate->confirmed = 1;
            $reservationUpdate->confirmation_date = now();
            $reservationUpdate->save();
        }

        event(new updateReservationEvent($event->reservationId));

        //TODO: Enviar correo
    }
}
