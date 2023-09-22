<?php

namespace App\Http\Livewire;

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
        $this->wait = $params['wait'];
        $this->editReservation = $params['editReservation'] ?? null;
        $this->programmationId = $params['programmationId'] ?? null;

        //consultar la reservacion
        $this->reservation = Reservation::with('member')->where('id',$this->reservationId)->first();
        $this->members = $this->reservation->member;
        foreach ($this->members as $key => $value) {
            $this->nameMember[] =  $value->name;
            $this->documentMember[] =  $value->document;
            $this->minor[] =  $value->is_minor;
        }
        if ($this->reservation) {
            $this->quota = $this->reservation->quota;

            foreach (range(count($this->minor), $this->quota) as $index) {
                $this->minor[$index] = 1;
            }
        }
        //  
     //   dd($this->members);
        $this->userId = $this->reservation->user_id;
        $this->dataUser($this->reservation->user_id);

        $this->openMembers = true;
    }
    public function save()
    {
        foreach ($this->documentMember as $key => $value) {
         
       // for ($i = 0; $i < count($this->minor); $i++) {
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
        $correo = new ReservationVerification($this->reservationId);
        //Correo del usuario
        $respose = Mail::to($this->email)->send($correo);

        $this->resetDates();
        $this->emitTo('tablet-register', 'render');
        $this->emit('alert', 'reservacion creada con éxito');
        $this->openMembers = false;
    }

    public function openModalCreateReservation()
    {

        $this->openMembers = false;
        $params = [
            'userId' => $this->userId,
            'email' => strtolower($this->email),
            'wait' => $this->wait,
            'reservationId'=> $this->reservationId,
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
