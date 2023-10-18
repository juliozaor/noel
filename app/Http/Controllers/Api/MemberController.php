<?php

namespace App\Http\Controllers\Api;

use App\Events\confirmReservationEvent;
use App\Events\updateProgrammingEvent;
use App\Events\updateReservationEvent;
use App\Http\Controllers\Controller;
use App\Mail\ReservationVerification;
use App\Models\Members;
use App\Models\MembersReservation;
use App\Models\QrCodes;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    
    public function store(Request $request)
    {
        $reservation = Reservation::find($request->reservation_id);
        
        $user = auth()->user(); // Accede al usuario autenticado

        $codes = [];
        $url = env('APP_URL').'/admin/events/qr/';

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
                $cod = Str::uuid();
                $qr =$url.$cod;
                $memberReservation = new MembersReservation([
                    'members_id' => $member->id,
                    'reservation_id' => $request->reservation_id
                ]);
                $memberReservation->save();

                $qrCode = new QrCodes([
                    'document'=>$memberRequest['document'],
                    'code_qr'=>$qr,
                    'code' => $cod,
                    'reservation_id'=> $request->reservation_id
                ]);
                $qrCode->save();

                $dateUser = [
                    'name' => $memberRequest['name'],
                    'qr' => $qr,
                    'isUser' => 0
                ];

                $codes[] = $dateUser;
            }
        }
        $cod = Str::uuid();
        $qrU = $url.$cod;
        $qrCode = new QrCodes([
            'document'=>$user->profile->document,
            'is_user'=>1,
            'code_qr'=> $qrU,
            'code' => $cod,
            'reservation_id'=> $request->reservation_id
        ]);
        $qrCode->save();

        $dateUserU = [
            'name' => $user->name,
            'qr' => $qrU,
            'isUser' => 1
        ];

        $codes[] = $dateUserU;

        if($reservation->programming_id !=1){
            event(new confirmReservationEvent($request->reservation_id));
      //  $correo = new ReservationVerification($request->reservation_id);
      $correo = new ReservationVerification($codes);
        //Correo del usuario
        $respose = Mail::to($user->email)->send($correo);

        }

        return response()->json([
            'status' => true,
            'message' => 'Members created successfully'
        ], 200);
    }
 

    public function updateMemberReservation(Request $request)
    {
        $user = auth()->user();
        $codes = [];
        $url = env('APP_URL').'/admin/events/qr/';
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
                $cod = Str::uuid();
                $qr =$url.$cod;
                $memberReservation = new MembersReservation([
                    'members_id' => $member->id,
                    'reservation_id' => $request->reservation_id
                ]);
                $memberReservation->save();

                $qrCode = new QrCodes([
                    'document'=>$memberRequest['document'],
                    'code_qr'=>$qr,
                    'code' => $cod,
                    'reservation_id'=> $request->reservation_id
                ]);
                $qrCode->save();

                $dateUser = [
                    'name' => $memberRequest['name'],
                    'qr' => $qr,
                    'isUser' => 0
                ];

                $codes[] = $dateUser;
            }
        }

        $cod = Str::uuid();
        $qrU = $url.$cod;
        $qrCode = new QrCodes([
            'document'=>$user->profile->document,
            'is_user'=>1,
            'code_qr'=> $qrU,
            'code' => $cod,
            'reservation_id'=> $request->reservation_id
        ]);
        $qrCode->save();
        $dateUserU = [
            'name' => $user->name,
            'qr' => $qrU,
            'isUser' => 1
        ];

        $codes[] = $dateUserU;

        if($reservation->programming_id !=1){
            event(new confirmReservationEvent($request->reservation_id));
      //  $correo = new ReservationVerification($request->reservation_id);
      $correo = new ReservationVerification($codes);
        //Correo del usuario
        $respose = Mail::to($user->email)->send($correo);

        }

       // event(new updateReservationEvent($request->reservation_id));

        return response()->json([
            'status' => true,
            'message' => 'update reservation'
        ], 200);
    }
}
