<?php

namespace App\Http\Livewire;

use App\Mail\ReservationVerification;
use App\Models\Members;
use App\Models\MembersReservation;
use App\Models\Profile;
use App\Models\Programming;
use App\Models\QrCodes;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CreateMembers extends Component
{


    public $openMembers = false;
    public $reservationId;
    public $reservation;
    public $quota;
    public $minor = [];
    public $nameMember = [];
    public $documentMember = [];
    public $notAttend = [];
    public $name, $document, $email, $userId;
    public $editReservation = false;
    public $programmationId = 1;
    public $members;
    protected $listeners = ['open', 'confirmDeletereservation'];
    public $wait = 0;
    public $codes = [];
    public $img = [];


    public function render()
    {
        return view('livewire.create-members');
    }

    public function open($params)
    {
        $this->resetDates();
        $this->reservationId = $params['reservationId'];
        $this->email = $params['email'];
        $this->wait = $params['wait'] ?? 0;
        $this->editReservation = $params['editReservation'] ?? false;
        $this->programmationId = $params['programmationId'] ?? null;

        //consultar la reservacion
        $this->reservation = Reservation::with('member')->where('id', $this->reservationId)->first();

        $this->members = $this->reservation->member;
        if (count($this->members) >= 1) {
            foreach ($this->members as $key => $value) {
                $this->nameMember[] =  $value->name;
                $this->documentMember[] =  $value->document;
                $this->minor[] =  $value->is_minor;
                $this->notAttend[] = false;
            }
        }
        $this->quota = $this->reservation->quota;

        foreach (range(count($this->minor), $this->quota) as $index) {
            $this->minor[$index] = 1;
            $this->notAttend[$index] = false;
        }
        //  
        //   dd($this->members);
        $this->userId = $this->reservation->user_id;
        $this->dataUser($this->reservation->user_id);

        $this->openMembers = true;
    }
    public function validateMembers()
    {
        if (!$this->editReservation) {
            $this->save();
        } else {
            $this->update();
        }
    }
    public function save()
    {
        try {
            $reservation = Reservation::where('id', $this->reservationId)
                ->with('programming')->first();

            foreach ($this->documentMember as $key => $value) {
                $date = date('Y-m-d');
                $time = date('H:i:s');
                if (isset($this->documentMember[$key]) && isset($this->nameMember[$key])) {

                    //Verificar si el miembro es un usuario y tienen una reserva activa
                    $profileUser = Profile::where('document', $this->documentMember[$key])->first();
                    if ($profileUser) {
                        $reservations = Reservation::with('programming')->where('user_id', $profileUser->user_id)->get();
                        foreach ($reservations as $reservation) {
                            if (
                                $date < $reservation->programming->initial_date ||
                                ($date == $reservation->programming->initial_date &&
                                    $time < $reservation->programming->initial_time)
                            ) {
                                session()->flash('message', 'El miembro ' . $this->nameMember[$key]
                                    . ' tiene una reserva activa');
                                return;
                            }
                        }
                    }

                    $member = Members::where('document', $this->documentMember[$key])->first();

                    if (!$member) {
                        $member = new Members([
                            "name" => $this->nameMember[$key],
                            "document" => $this->documentMember[$key],
                            "is_minor" => $this->minor[$key]
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
                                session()->flash('message', 'El miembro ' . $this->nameMember[$key]
                                    . ' tiene una reserva activa');
                                return;
                            }
                        }
                    }


                    $exists = Members::find($member->id)
                        ->reservation()
                        ->wherePivot('reservation_id', $this->reservationId)
                        ->exists();

                    if (!$exists) {
                        $cod = Str::uuid();
                        $qr = config('app.url') . '/admin/events/qr/' . $cod;
                        $memberReservation = new MembersReservation([
                            'members_id' => $member->id,
                            'reservation_id' => $this->reservationId
                        ]);
                        $memberReservation->save();
                        $this->createImgQr($qr, $cod);

                        $qrCode = new QrCodes([
                            'document' => $this->documentMember[$key],
                            'code_qr' => $qr,
                            'code' => $cod,
                            'reservation_id' => $this->reservationId
                        ]);
                        $qrCode->save();

                        $dateUser = [
                            'name' => $this->nameMember[$key],
                            'qr' => 'temp/' . $cod . '.png',
                            'isUser' => 0
                        ];

                        $this->codes[] = $dateUser;
                    }
                }
            }

            $user = User::findOrFail($this->userId);

            $codU = Str::uuid();
            $qrU = config('app.url') . '/admin/events/qr/' . $codU;

            $this->createImgQr($qrU, $codU);

            $qrCode = new QrCodes([
                'document' => $user->profile->document,
                'is_user' => 1,
                'code_qr' => $qrU,
                'code' => $codU,
                'reservation_id' => $this->reservationId
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

            $this->codes[] = $dateUserU;
            if ($reservation->programming_id != 1) {
                $this->confirmReservation($this->reservationId);
                $correo = new ReservationVerification($this->codes);
                $respose = Mail::to($this->email)->send($correo);
            }

            $this->emitTo('tablet-register', 'render');
            $this->emit('alert', 'reservacion creada con éxito', 'success');
            $this->emit('reiniciar');
            $this->openMembers = false;
            $this->resetDatesInAll();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function update()
    {
        try {
            $reservation = Reservation::where('id', $this->reservationId)
                ->with('programming')->first();
            if ($reservation) {
                $reservation->member()->detach();
                $reservation->qrCodes()->delete();
            }

            $date = date('Y-m-d');
            $time = date('H:i:s');

            foreach ($this->documentMember as $key => $value) {

                if (isset($this->documentMember[$key]) && isset($this->nameMember[$key])) {

                    $profileUser = Profile::where('document', $this->documentMember[$key])->first();
                    if ($profileUser) {
                        $reservations = Reservation::with('programming')->where('user_id', $profileUser->user_id)->get();
                        foreach ($reservations as $reservation) {
                            if (
                                $date < $reservation->programming->initial_date ||
                                ($date == $reservation->programming->initial_date &&
                                    $time < $reservation->programming->initial_time)
                            ) {
                                session()->flash('message', 'El miembro ' . $this->nameMember[$key]
                                    . ' tiene una reserva activa');
                                return;
                            }
                        }
                    }

                    $member = Members::where('document', $this->documentMember[$key])->first();
                    if (!$member) {
                        $member = new Members([
                            "name" => $this->nameMember[$key],
                            "document" => $this->documentMember[$key],
                            "is_minor" => $this->minor[$key]
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
                                session()->flash('message', 'El miembro ' . $this->nameMember[$key]
                                    . ' tiene una reserva activa');
                                return;
                            }
                        }
                    }


                    if (!$this->notAttend[$key]) {
                        $cod = Str::uuid();
                        $qr = config('app.url') . '/admin/events/qr/' . $cod;
                        $memberReservation = new MembersReservation([
                            'members_id' => $member->id,
                            'reservation_id' => $this->reservationId
                        ]);
                        $memberReservation->save();

                        $this->createImgQr($qr, $cod);

                        $this->img[] = $cod;
                        $qrCode = new QrCodes([
                            'document' => $this->documentMember[$key],
                            'code_qr' => $qr,
                            'code' => $cod,
                            'reservation_id' => $this->reservationId
                        ]);
                        $qrCode->save();

                        $dateUser = [
                            'name' => $this->nameMember[$key],
                            'qr' => 'temp/' . $cod . '.png',
                            'isUser' => 0
                        ];

                        $this->codes[] = $dateUser;
                    }
                }
            }

            $user = User::findOrFail($this->userId);

            $codU = Str::uuid();
            $qrU = config('app.url') . '/admin/events/qr/' . $codU;

          
            $this->createImgQr($qrU, $codU);
            $this->img[] = $codU;

            $qrCode = new QrCodes([
                'document' => $user->profile->document,
                'is_user' => 1,
                'code_qr' => $qrU,
                'code' => $codU,
                'reservation_id' => $this->reservationId
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

            $this->codes[] = $dateUserU;

            if ($reservation->programming_id != 1) {
                $this->confirmReservation($this->reservationId);
                $correo = new ReservationVerification($this->codes);

                $respose = Mail::to($this->email)->send($correo);
                //  dd($this->img);
                // dd($this->email,$respose);
            }

            $this->emitTo('tablet-register', 'render');
            $this->emit('alert', 'reservacion actualizada con éxito', 'success');
            //$this->resetDates();
            $this->emit('reiniciar');
            $this->openMembers = false;
            $this->resetDatesInAll();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function openModalCreateReservation()
    {

        $this->openMembers = false;
        $params = [
            'userId' => $this->userId,
            'email' => strtolower($this->email),
            'wait' => $this->wait,
            'reservationId' => $this->reservationId,
            'editReservation' => $this->editReservation,
            'programmationId' => $this->programmationId,
        ];
        $this->emitTo('create-reservation', 'open', $params);
    }

    public function dataUser($userId)
    {
        $profile = Profile::where('user_id', $this->reservation->user_id)->first();
        $this->name = $profile->user->name;
        $this->document = $profile->document;
    }

    public function resetDates()
    {
        $this->reset([
            'reservationId', 'reservation', 'quota', 'minor', 'nameMember',
            'documentMember', 'name', 'document', 'email', 'codes'
        ]);
    }

    public function resetDatesInAll()
    {
        //  $this->emit('resetDates');
        $this->openMembers = false;
    }

    public function delectRegister()
    {
        $this->emit('delReservation', $this->reservationId);
    }

    public function confirmDeletereservation($reservationId)
    {
        $reservation = Reservation::find($reservationId);

        // Si se encontró la resrva, elimínalo
        if ($reservation) {
            $idProgramming = $reservation->programming_id;
            $reservation->delete();
            $this->updateProgrammingQuota($idProgramming);
            $this->emitTo('tablet-register', 'render');
            $this->openMembers = false;
            // $this->resetDatesInAll();
        }
        $this->emit('reiniciar');
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

    public function createImgQr($qr, $cod)
    {
        // Genera el código QR en formato SVG
        $qrCodeSvg = QrCode::format('png')->size(150)->generate($qr, 'temp/' . $cod . '.png');
    }
}
