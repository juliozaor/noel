<?php

namespace App\Http\Controllers\Api;

use App\Events\updateProgrammingEvent;
use App\Events\updateReservationEvent;
use App\Http\Controllers\Controller;
use App\Mail\ReservationVerification;
use App\Models\Members;
use App\Models\MembersReservation;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MemberController extends Controller
{
    
    public function store(Request $request)
    {
        $reservation = Reservation::find($request->reservation_id);
        
        $user = auth()->user(); // Accede al usuario autenticado

        if($user->id != $reservation->user_id){
            return response()->json([
                'status' => false,
                'message' => 'La reservación no pertenece a este usuario'
            ], 400);
        }

        if(sizeof($request->members) > $reservation->quota - 1){
            return response()->json([
                'status' => false,
                'message' => 'El número de miembros supera a la reservada'
            ], 400);
        }

        foreach ($request->members as $memberRequest) {
            $member = Members::where('document', $memberRequest['document'])->first();
            if (!$member) {
                $member = new Members([
                    "name" => $memberRequest['name'],
                    "document" => $memberRequest['document'],
                    "is_minor" => $memberRequest['is_minor']
                ]);
                $member->save();
            }

            $exists = Members::find($member->id)
                ->reservation()
                ->wherePivot('reservation_id', $request->reservation_id)
                ->exists();

            if (!$exists) {
                $memberReservation = new MembersReservation([
                    'members_id' => $member->id,
                    'reservation_id' => $request->reservation_id
                ]);
                $memberReservation->save();
            }
        }

        if($reservation->programming_id !=1){

      //  $correo = new ReservationVerification($request->reservation_id);
        //Correo del usuario
     //   $respose = Mail::to($user->email)->send($correo);

        }

        return response()->json([
            'status' => true,
            'message' => 'Members created successfully'
        ], 200);
    }
 

    public function updateMemberReservation(Request $request)
    {


        $reservation = Reservation::find($request->reservation_id);
        if (!$reservation) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontro la reservacion'
            ], 404);
        }
        if ($reservation) {
            $reservation->member()->detach();
        }
        foreach ($request->members as $memberRequest) {
            $member = Members::where('document', $memberRequest['document'])->first();
            if (!$member) {
                $member = new Members([
                    "name" => $memberRequest['name'],
                    "document" => $memberRequest['document'],
                    "is_minor" => $memberRequest['is_minor']
                ]);
                $member->save();
            } else {
                $member->update($memberRequest);
            }

            if (!$memberRequest['notAttend']) {
                $memberReservation = new MembersReservation([
                    'members_id' => $member->id,
                    'reservation_id' => $request->reservation_id
                ]);
                $memberReservation->save();
            }
        }

        event(new updateReservationEvent($request->reservation_id));

        return response()->json([
            'status' => true,
            'message' => 'update reservation'
        ], 200);
    }
}
