<?php

namespace App\Http\Controllers\Api;

use App\Events\updateProgrammingEvent;
use App\Events\updateReservationEvent;
use App\Http\Controllers\Controller;
use App\Models\Programming;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $programming = Programming::findOrfail($request->programming_id);
        } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => 'No se encontro la programación'
                ], 404);
    
        }
        $user = auth()->user(); // Accede al usuario autenticado


        $isreservation = Reservation::where('programming_id', $request->programming_id)
        ->where('user_id',$user->id)->get();

        if(sizeof($isreservation) >= 1 ){
            return response()->json(['status' => true,'message'=>'Ya existe una reserva para esta programacion'],201);
        }
        
        if ($programming->quota_available >= $request->quota) {

            $reservation = new Reservation();
            $reservation->quota = $request->quota;
            $reservation->programming_id = $request->programming_id;
            $reservation->user_id = $user->id;
            $reservation->reservation_date = now();
            $reservation->save();

            $programming->quota_available -= $request->quota;
            $programming->save();

            return response()->json([
                'status' => true,
                'message' => 'Reservation created successfully',
                "data" => $reservation
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'no slots available',
        ], 400);
    }

   

    public function confirmet(string $reservation)
    {
        $reservationUpdate = Reservation::findOrFail($reservation);

        if ($reservationUpdate) {
           // $membersCount = $reservationUpdate->members()->count();
            $reservationUpdate->confirmed = true;
            $reservationUpdate->confirmation_date = now();
          //  $reservationUpdate->quota = $membersCount+1;
            $reservationUpdate->save();
        }

        event(new updateReservationEvent($reservation));
       // event(new updateProgrammingEvent($reservationUpdate->programming_id));
        //redireccionar a la pagina del front y enviar correo de registro exitoso
        return response()->json([
            'status' => true,
            'message' => 'confirmed reservation',
            "data" => $reservation
        ], 200);
    }

   

    public function perUser(int $userId)
    {
        $reservations = Reservation::where('user_id', $userId)->get();

        $reservationData = [];

        foreach ($reservations as $reservation) {
            
            $programming = $reservation->programming;
        
            if ($programming) {
                $reservationData[] = [
                    'id' => $reservation->id,
                    'fecha' => $programming->initial_date,
                    'hora' => $programming->initial_time,
                ];
            }
        }
        return response()->json([
            'status' => true,
            "data" => $reservationData
        ], 200);
    }

    public function getMembersByReservation(int $reservationId)
    {
        
        $reservation = Reservation::findOrFail($reservationId);
        $members = $reservation->member;

        return $members;
    }

    public function waitingList(Request $request)
    {
            $user = auth()->user(); // Accede al usuario autenticado

            $reservation = new Reservation();
            $reservation->quota = $request->quota;
            $reservation->programming_id = 1;
            $reservation->user_id = $user->id;
            $reservation->reservation_date = now();
            $reservation->save();

            return response()->json([
                'status' => true,
                'message' => 'Reservation created successfully',
                "data" => $reservation
            ], 200);
        
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return response()->json([
            'status' => true,
            'message' => 'Reservation delete successfully'
        ], 200);
        
    }

}
