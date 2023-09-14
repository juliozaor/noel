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
    public $name, $document, $email, $userId;
    protected $listeners = ['open', 'resetDates'];

    public function render()
    {
        return view('livewire.create-members');
    }

    public function open($params)
    {
        $this->reservationId = $params['reservationId'];
        $this->email = $params['email'];

        //consultar la reservacion
        $this->reservation = Reservation::find($this->reservationId);
        if ($this->reservation) {
            $this->quota = $this->reservation->quota;
            foreach (range(2, $this->quota) as $index) {
                $this->minor[$index] = 1;
            }
        }
$this->userId = $this->reservation->user_id;
        $this->dataUser($this->reservation->user_id);

        $this->openMembers = true;
    }
    public function save()
    {

        for ($i = 0; $i < count($this->minor); $i++) {
            if (isset($this->documentMember[$i]) && isset($this->nameMember[$i])) {

                $member = Members::where('document', $this->documentMember[$i])->first();
                if (!$member) {
                    $member = new Members([
                        "name" => $this->nameMember[$i],
                        "document" => $this->documentMember[$i],
                        "is_minor" => $this->minor[$i]
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
        $respose = Mail::to( $this->email)->send($correo);

       
       /*  $this->emitTo('create-members','resetDates');
        $this->emitTo('create-reservation','resetDates'); */
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
        ];
        $this->emitTo('create-reservation', 'open', $params);
    }

    public function dataUser($userId)
    {
        $profile = Profile::where('user_id', $this->reservation->user_id)->first();
        $this->name = $profile->user->name;
        $this->document = $profile->document;
    }

    private function resetDates()
    {
        $this->reset(['reservationId', 'reservation', 'quota', 'minor', 'nameMember',
        'documentMember', 'name', 'document', 'email']);

    }
}
