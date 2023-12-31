<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ReservationDeletedConfirmation;
use App\Models\Profile;
use App\Models\Programming;
use App\Models\QrCodes;
use App\Models\Reservation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservationController extends Controller
{


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $programming = Programming::findOrfail($request->programming_id);


            $date = date('Y-m-d');
            $time = date('H:i:s');

            if (
                $date > $programming->initial_date ||
                ($date == $programming->initial_date && $time > $programming->initial_time)
            ) {
                return response()->json([
                    'status' => false,
                    'message' => 'Este evento ya paso',
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontro la programación'
            ], 404);
        }
        $user = auth()->user(); // Accede al usuario autenticado

        $reservations = Reservation::with('programming')->where('user_id', $user->id)->get();

        if (count($reservations) >= 1) {
            if ($user->profile && $user->profile->is_collaborator == 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'El usuario es un colaborador y ya tiene una reserva en el sistema',
                ], 400);
            }

            $programmingId = $request->programming_id;
            $hasProgramming = $reservations->contains(function ($reservation) use ($programmingId) {
                return $reservation->programming && $reservation->programming->id === $programmingId;
            });

            if ($hasProgramming) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya existe una reserva para esta programacion',
                ], 400);
            }

            foreach ($reservations as $reservation) {
                if (
                    $date < $reservation->programming->initial_date ||
                    ($date == $reservation->programming->initial_date &&
                        $time < $reservation->programming->initial_time)
                ) {
                    return response()->json([
                        'status' => false,
                        'message' => 'El usuario tiene una reserva activa',
                    ], 400);
                }
            }
        }

        if ($request->quota > 5) {
            return response()->json([
                'status' => false,
                'message' => 'El usuario no puede tener mas de 5 cupos',
            ], 400);
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
                'message' => 'Reservación creada exitosamente',
                "data" => $reservation
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'no hay cupos disponibles',
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


        //redireccionar a la pagina del front y enviar correo de registro exitoso
        return response()->json([
            'status' => true,
            'message' => 'confirmed reservation',
            "data" => $reservation
        ], 200);
    }

    public function perUser(int $userId)
    {
        $reservations = Reservation::where('user_id', $userId)->with('member')->get();

        $reservationData = [];

        foreach ($reservations as $reservation) {
            $programming = $reservation->programming;
            if ($programming) {
                $membersData = $reservation->member->map(function ($member) {
                    return [
                        'nombre' => $member->name,
                        'documento' => $member->document,
                        'es_menor' => $member->is_minor,
                        'fecha_creacion' => $member->created_at->format('Y-m-d H:i:s'),
                    ];
                });
                $reservationData[] = [
                    'id' => $reservation->id,
                    'eventoId' => $programming->id,
                    'fecha' => $programming->initial_date,
                    'hora' => $programming->initial_time,
                    'cupos' => $reservation->quota,
                    'listaEspera' => $programming->id > 1 ? false : true,
                    'miembros' => $membersData
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

        $reservations = Reservation::with('programming')->where('user_id', $user->id)->get();
        $date = date('Y-m-d');
        $time = date('H:i:s');
        if (count($reservations) >= 1) {
            if ($user->profile->is_collaborator == 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'El usuario es un colaborador y ya tiene una reserva en el sistema',
                ], 400);
            }

            $programmingId = 1;
            $hasProgramming = $reservations->contains(function ($reservation) use ($programmingId) {
                return $reservation->programming && $reservation->programming->id === $programmingId;
            });

            if ($hasProgramming) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya el usuario se encuentra en lista de espera',
                ], 400);
            }

            foreach ($reservations as $reservation) {
                if (
                    $date < $reservation->programming->initial_date ||
                    ($date == $reservation->programming->initial_date &&
                        $time < $reservation->programming->initial_time)
                ) {
                    return response()->json([
                        'status' => false,
                        'message' => 'El usuario tiene una reserva activa',
                    ], 400);
                }
            }
        }

        if ($user->profile->is_collaborator == 1 && $request->quota > 5) {
            return response()->json([
                'status' => false,
                'message' => 'El usuario no puede tener mas de 5 cupos',
            ], 400);
        }

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
        try {
            $user = auth()->user();
            $reservation = Reservation::with('programming')->findOrFail($id);
            $idProgramming = $reservation->programming_id;
            if ($reservation->programming->initial_date < date('Y-m-d')) {
                return response()->json([
                    'status' => false,
                    'message' => 'No se puede eliminar una reserva de un evento que ya paso',
                ], 400);
            }
            if ($idProgramming == 1) {
                $reservation->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Reservation delete successfully'
                ], 200);
            }
            if ($reservation->user_id != $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'No se puede eliminar una reserva que no le pertenece',
                ], 400);
            }

            $correo = new ReservationDeletedConfirmation([
                'date' => $reservation->programming->initial_date,
                'time' => $reservation->programming->initial_time,
                'quota' => $reservation->quota,
                'name' => $user->name,
            ]);
            $reservation->delete();
            Mail::to($user->email)->send($correo);
            $this->updateProgrammingQuota($idProgramming);

            return response()->json([
                'status' => true,
                'message' => 'Reservation delete successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'No se pudo eliminar la reserva',
                'detail' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProgrammingQuota($programmingId)
    {

        if ($programmingId <> 1) {
            $programming = Programming::findOrFail($programmingId);
            $totalReservationsQuota = $programming->reservation()->sum('quota');
            $newQuotaAvailable = $programming->quota - $totalReservationsQuota;


            $programming->update([
                'quota_available' => $newQuotaAvailable
            ]);
        }
    }

    public function generateQrbyCode($code)
    {
        $url = config('app.url') . '/admin/events/qr/';
        $reservation = QrCodes::where('code', $code)->first();
        if ($reservation) {
            $qrCode = QrCode::format('png')->size(150)->generate($url . $reservation->code, 'temp/' . $reservation->code . '.png');
            return response()->json([
                'status' => true,
                'message' => 'Qr generado',
                'data' => config('app.url') . '/temp/' . $reservation->code . '.png'
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'No se encontro la reserva'
        ], 404);
    }
}
