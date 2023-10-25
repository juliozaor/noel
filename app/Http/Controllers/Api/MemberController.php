<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Mail\ReservationVerification;
use App\Models\Members;
use App\Models\MembersReservation;
use App\Models\Profile;
use App\Models\Programming;
use App\Models\QrCodes;
use App\Models\Reservation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemberController extends Controller
{

    public function store(Request $request)
    {
        try {
            $reservation = Reservation::where('id', $request->reservation_id)
                ->with('programming')->first();

            if (!$reservation) {
                return response()->json([
                    'status' => false,
                    'message' =>  'La reservación no existe'
                ], 400);
            }

            $user = auth()->user(); // Accede al usuario autenticado

            $codes = [];
            $url = config('app.url') . '/admin/events/qr/';

            if ($user->id != $reservation->user_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'La reservación no pertenece a este usuario'
                ], 400);
            }

            if (sizeof($request->members) > $reservation->quota - 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'El número de miembros supera a la reservada'
                ], 400);
            }

            $date = date('Y-m-d');
            $time = date('H:i:s');

            foreach ($request->members as $memberRequest) {

                //Verificar si el miembro es un usuario y tienen una reserva activa
                $profileUser = Profile::where('document', $memberRequest['document'])->first();
                if ($profileUser) {
                    $reservations = Reservation::with('programming')->where('user_id', $profileUser->user_id)->get();
                    foreach ($reservations as $reservation) {
                        if (
                            $date < $reservation->programming->initial_date ||
                            ($date == $reservation->programming->initial_date &&
                                $time < $reservation->programming->initial_time)
                        ) {
                            return response()->json([
                                'status' => false,
                                'message' => 'El miembro ' . $memberRequest['name']
                                    . ' tiene una reserva activa'
                            ], 400);
                        }
                    }
                }




                $member = Members::where('document', $memberRequest['document'])->first();
                if (!$member) {
                    $member = new Members([
                        "name" => $memberRequest['name'],
                        "document" => $memberRequest['document'],
                        "is_minor" => $memberRequest['is_minor']
                    ]);
                    $member->save();
                } else {
                    //Validar si el usuario ya esta en una programacion activa
                    $programmings = Programming::select('programmings.*')
                        ->join('reservations', 'programmings.id', '=', 'reservations.programming_id')
                        ->join('members_reservation', 'reservations.id', '=', 'members_reservation.reservation_id')
                        ->join('members', 'members_reservation.members_id', '=', 'members.id')
                        ->where('members.id', $member->id)
                        ->get();


                    foreach ($programmings as $programming) {
                        if (
                            $date < $programming->initial_date ||
                            ($date == $programming->initial_date &&
                                $time < $programming->initial_time)
                        ) {
                            return response()->json([
                                'status' => false,
                                'message' => 'El miembro ' . $memberRequest['name']
                                    . ' tiene una reserva activa'
                            ], 400);
                        }
                    }
                }

                $exists = Members::find($member->id)
                    ->reservation()
                    ->wherePivot('reservation_id', $request->reservation_id)
                    ->exists();

                if (!$exists) {
                    $cod = Str::uuid();
                    $qr = $url . $cod;
                    $memberReservation = new MembersReservation([
                        'members_id' => $member->id,
                        'reservation_id' => $request->reservation_id
                    ]);
                    $memberReservation->save();
                    $this->createImgQr($qr, $cod);

                    $qrCode = new QrCodes([
                        'document' => $memberRequest['document'],
                        'code_qr' => $qr,
                        'code' => $cod,
                        'reservation_id' => $request->reservation_id
                    ]);
                    $qrCode->save();

                    $dateUser = [
                        'name' => $memberRequest['name'],
                        'qr' => 'temp/' . $cod . '.png',
                        'isUser' => 0
                    ];

                    $codes[] = $dateUser;
                }
            }
            $codU = Str::uuid();
            $qrU = $url . $codU;

            $this->createImgQr($qrU, $codU);

            $qrCode = new QrCodes([
                'document' => $user->profile->document,
                'is_user' => 1,
                'code_qr' => $qrU,
                'code' => $codU,
                'reservation_id' => $request->reservation_id
            ]);
            $qrCode->save();

            $dateUserU = [
                'name' => $user->name,
                'qr' => 'temp/' . $codU . '.png',
                'isUser' => 1,
                'quota' => $reservation->quota,
                'date' => $reservation->programming->initial_date,
                'time' => $reservation->programming->initial_time

            ];

            $codes[] = $dateUserU;

            if ($reservation->programming_id != 1) {

                $this->confirmReservation($request->reservation_id);

                $correo = new ReservationVerification($codes);
                $respose = Mail::to($user->email)->send($correo);
            }

            return response()->json([
                'status' => true,
                'message' => 'Members created successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function updateMemberReservation(Request $request)
    {
        $user = auth()->user();
        $codes = [];
        $url = config('app.url') . '/admin/events/qr/';
        $reservation = Reservation::where('id', $request->reservation_id)
            ->with('programming')->first();


        if (!$reservation) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontro la reservacion'
            ], 404);
        }
        if ($reservation) {
            $reservation->member()->detach();
            $reservation->qrCodes()->delete();
        }
        $date = date('Y-m-d');
        $time = date('H:i:s');
        foreach ($request->members as $memberRequest) {
            //Verificar si el miembro es un usuario y tienen una reserva activa
            $profileUser = Profile::where('document', $memberRequest['document'])->first();
            if ($profileUser) {
                $reservations = Reservation::with('programming')->where('user_id', $profileUser->user_id)->get();
                foreach ($reservations as $reservation) {
                    if (
                        $date < $reservation->programming->initial_date ||
                        ($date == $reservation->programming->initial_date &&
                            $time < $reservation->programming->initial_time)
                    ) {
                        return response()->json([
                            'status' => false,
                            'message' => 'El miembro ' . $memberRequest['name']
                                . ' tiene una reserva activa'
                        ], 400);
                    }
                }
            }

            $member = Members::where('document', $memberRequest['document'])->first();
            if (!$member) {
                $member = new Members([
                    "name" => $memberRequest['name'],
                    "document" => $memberRequest['document'],
                    "is_minor" => $memberRequest['is_minor']
                ]);
                $member->save();
            } else {
                //Validar si el usuario ya esta en una programacion activa
                $programmings = Programming::select('programmings.*')
                    ->join('reservations', 'programmings.id', '=', 'reservations.programming_id')
                    ->join('members_reservation', 'reservations.id', '=', 'members_reservation.reservation_id')
                    ->join('members', 'members_reservation.members_id', '=', 'members.id')
                    ->where('members.id', $member->id)
                    ->get();


                foreach ($programmings as $programming) {
                    if (
                        $date < $programming->initial_date ||
                        ($date == $programming->initial_date &&
                            $time < $programming->initial_time)
                    ) {
                        return response()->json([
                            'status' => false,
                            'message' => 'El miembro ' . $memberRequest['name']
                                . ' tiene una reserva activa'
                        ], 400);
                    }
                }
                // $member->update($memberRequest);

            }

            if (!$memberRequest['notAttend']) {
                $cod = Str::uuid();
                $qr = $url . $cod;
                $memberReservation = new MembersReservation([
                    'members_id' => $member->id,
                    'reservation_id' => $request->reservation_id
                ]);
                $memberReservation->save();

                $this->createImgQr($qr, $cod);

                $qrCode = new QrCodes([
                    'document' => $memberRequest['document'],
                    'code_qr' => $qr,
                    'code' => $cod,
                    'reservation_id' => $request->reservation_id
                ]);
                $qrCode->save();

                $dateUser = [
                    'name' => $memberRequest['name'],
                    'qr' => 'temp/' . $cod . '.png',
                    'isUser' => 0
                ];

                $codes[] = $dateUser;
            }
        }

        $codU = Str::uuid();
        $qrU = $url . $codU;

        $this->createImgQr($qrU, $codU);
        $qrCode = new QrCodes([
            'document' => $user->profile->document,
            'is_user' => 1,
            'code_qr' => $qrU,
            'code' => $codU,
            'reservation_id' => $request->reservation_id
        ]);
        $qrCode->save();
        $dateUserU = [
            'name' => $user->name,
            'qr' => 'temp/' . $codU . '.png',
            'isUser' => 1,
            'quota' => $reservation->quota,
            'date' => $reservation->programming->initial_date,
            'time' => $reservation->programming->initial_time
        ];

        $codes[] = $dateUserU;

        if ($reservation->programming_id != 1) {
            $this->confirmReservation($request->reservation_id);
            $correo = new ReservationVerification($codes);
            //Correo del usuario
            $respose = Mail::to($user->email)->send($correo);
        }


        return response()->json([
            'status' => true,
            'message' => 'update reservation'
        ], 200);
    }

    public function confirmReservation($reservationId)
    {
        $reservationUpdate = Reservation::findOrFail($reservationId);
        $membersCount = $reservationUpdate->member()->count();

        $reservationUpdate->quota = $membersCount + 1;
        $reservationUpdate->confirmed = 1;
        $reservationUpdate->confirmation_date = now();
        $reservationUpdate->save();


        if ($reservationUpdate->programming_id <> 1) {
            $programming = Programming::findOrFail($reservationUpdate->programming_id);
            $totalReservationsQuota = $programming->reservation()->sum('quota');
            $newQuotaAvailable = $programming->quota - $totalReservationsQuota;


            $programming->update([
                'quota_available' => $newQuotaAvailable
            ]);
        }
    }

    public function createImgQr($qr, $cod)
    {
        // Genera el código QR en formato SVG
        $qrCodeSvg = QrCode::format('png')->size(150)->generate($qr, 'temp/' . $cod . '.png');
    }
}
