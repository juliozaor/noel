<?php

namespace App\Http\Livewire;

use App\Events\confirmReservationEvent;
use App\Events\updateReservationEvent;
use App\Mail\ReservationVerification;
use App\Models\Members;
use App\Models\MembersReservation;
use App\Models\Profile;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

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
    protected $listeners = ['open', 'resetDates'];
    public $wait = 0;

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
        foreach ($this->members as $key => $value) {
            $this->nameMember[] =  $value->name;
            $this->documentMember[] =  $value->document;
            $this->minor[] =  $value->is_minor;
            $this->notAttend[] = false;
        }
        if ($this->reservation) {
            $this->quota = $this->reservation->quota;

            foreach (range(count($this->minor), $this->quota) as $index) {
                $this->minor[$index] = 1;
                $this->notAttend[$index] = false;
            }
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
        // TODO: notificar que no hay miembros

        foreach ($this->documentMember as $key => $value) {

            if (isset($this->documentMember[$key]) && isset($this->nameMember[$key])) {

                $member = Members::where('document', $this->documentMember[$key])->first();
                if (!$member) {
                    $member = new Members([
                        "name" => $this->nameMember[$key],
                        "document" => $this->documentMember[$key],
                        "is_minor" => $this->minor[$key]
                    ]);
                    $member->save();
                }

                $exists = Members::find($member->id)
                    ->reservation()
                    ->wherePivot('reservation_id', $this->reservationId)
                    ->exists();

                if (!$exists) {
                    $memberReservation = new MembersReservation([
                        'members_id' => $member->id,
                        'reservation_id' => $this->reservationId
                    ]);
                    $memberReservation->save();
                }
            }
        }
        event(new confirmReservationEvent($this->reservationId));
        $correo = new ReservationVerification($this->reservationId);
        $respose = Mail::to($this->email)->send($correo);
        $this->emitTo('tablet-register', 'render');
        $this->emit('alert', 'reservacion creada con éxito');
        $this->openMembers = false;
        $this->resetDates();
        $this->resetDatesInAll();
    }

    public function update()
    {
        // TODO: notificar que no hay miembros


        $reservation = Reservation::find($this->reservationId);
        if ($reservation) {
            $reservation->member()->detach();
        }


        foreach ($this->documentMember as $key => $value) {

            if (isset($this->documentMember[$key]) && isset($this->nameMember[$key])) {

                $member = Members::where('document', $this->documentMember[$key])->first();
                if (!$member) {
                    $member = new Members([
                        "name" => $this->nameMember[$key],
                        "document" => $this->documentMember[$key],
                        "is_minor" => $this->minor[$key]
                    ]);
                    $member->save();
                } else {
                    $member->update([
                        "name" => $this->nameMember[$key],
                        "document" => $this->documentMember[$key],
                        "is_minor" => $this->minor[$key]
                    ]);
                }


                if (!$this->notAttend[$key]) {
                    $memberReservation = new MembersReservation([
                        'members_id' => $member->id,
                        'reservation_id' => $this->reservationId
                    ]);
                    $memberReservation->save();
                }
            }
        }
        //TODO:Confirmar reserva al crear los miembros
        /*   $correo = new ReservationVerification($this->reservationId);
        //Correo del usuario
        $respose = Mail::to($this->email)->send($correo);
 */
        event(new updateReservationEvent($this->reservationId));
        $correo = new ReservationVerification($this->reservationId);
        $respose = Mail::to($this->email)->send($correo);
        $this->emitTo('tablet-register', 'render');
        $this->emit('alert', 'reservacion creada con éxito');
        $this->resetDates();
        $this->openMembers = false;
        $this->resetDatesInAll();
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
            'documentMember', 'name', 'document', 'email'
        ]);
    }

    public function resetDatesInAll()
    {
        $this->openMembers = false;
        $this->emit('resetDates');
    }
}
