<?php

namespace App\Http\Livewire;

use App\Models\Programming;
use App\Models\Reservation;
use Livewire\Component;

class CreateReservation extends Component
{
    public $openReservation = false;
    public $programmings = [];
    public $quota;
    public $quotaAvailable;
    public $reserverdQuota =null;
    public $selectedProgramming;
    public $mensaje = '';
    public $userId;
    public $email;
    public $wait = 0;
    public $reservationId;
    public $editReservation = false;
    public $programmationId = 1;
    protected $listeners = ['open', 'resetDates'];


    protected function rules()
    {
        $rules = [
            'quota' => ['required', 'integer'],
        ];
    
        $rules['quotaAvailable'] = ['required', 'integer'];
        $rules['quota'] = [
            'required',
            'integer',
            function ($attribute, $value, $fail) {
                if ($this->quotaAvailable < $value) {
                    $fail("Has superado la cuota disponible.");
                }
                if ($this->reserverdQuota && ($this->reserverdQuota > intval($value))) {
                    $fail("La cuota debe ser mayor o igual a la reservada.");
                }
            },
        ];
    
        return $rules;
    }


    public function loadReservations(){
        if ($this->editReservation) {
            $reservation = Reservation::with('programming')->with('user')
            ->where('user_id', $this->userId)
            ->where('programming_id', $this->programmationId)
            ->first();
            if ($reservation) {
                $programmings = Programming::where('id', $this->programmationId)->get();

              /*   $programmingsUser[] = (object)array(
                    "id" =>$reservation->programming->id,
                    "initial_date"=>$reservation->programming->initial_date,
                    "initial_time"=>$reservation->programming->initial_time,
                    "quota_available"=>$reservation->programming->quota_available,
                ); */

                $this->quotaAvailable = $programmings[0]->quota_available;
                $this->selectedProgramming =  $programmings[0]->id;
                $this->quota = $reservation->quota;
                $this->reserverdQuota = $reservation->quota;

                $this->programmings = $programmings;
            }

        }else{
            if ($this->programmationId) {
                $programmings = Programming::where('id', $this->programmationId)->get();
                $this->quotaAvailable = $programmings[0]->quota_available;
                $this->selectedProgramming =  $programmings[0]->id;
                $this->programmings = $programmings;
            }else{
            $programmings = Programming::where('quota_available', '>=', 1)->get();
            if (sizeof($programmings)) {
                $this->quotaAvailable = $programmings[0]->quota_available;
                $this->selectedProgramming =  $programmings[0]->id;
            }
            $this->programmings = $programmings;
        }
        }

    }

    public function render()
    {
        return view('livewire.create-reservation');
    }

    public function updatedSelectedProgramming($value)
    {
        $programming = Programming::find($value);
        $this->quotaAvailable = $programming->quota_available;
    }

    public function save()
    {
        $this->validate();
        
        if ($this->wait == 1) {
            if ($this->reservationId) {
                $isreservation = Reservation::where('programming_id', 1)
                    ->where('user_id', $this->userId)
                    ->where('id', $this->reservationId)->first();

                if ($isreservation) {
                    $memberCount = $isreservation->member()->count();

                    if ($memberCount >= 1 && !$this->editReservation) {

                        $this->emit('alert', 'Este usuario ya esta en lista de espera');
                    } else {

                        $isreservation->quota = $this->quota;
                        $isreservation->save();
                        $this->openModalMembers($isreservation->id);
                    }
                } else {

                    $reservation = new Reservation();
                    $reservation->quota = $this->quota;
                    $reservation->programming_id = 1;
                    $reservation->user_id = $this->userId;
                    $reservation->reservation_date = now();
                    $reservation->save();
                    $this->openModalMembers($reservation->id);
                }
            } else {
                $isreservation = Reservation::where('programming_id', 1)
                ->where('user_id', $this->userId)->first();
                if ($isreservation) {
                    $memberCount = $isreservation->member()->count();

                    if ($memberCount >= 1 && !$this->editReservation) {

                        $this->emit('alert', 'Este usuario ya esta en lista de espera');
                    } else {

                        $isreservation->quota = $this->quota;
                        $isreservation->save();
                        $this->openModalMembers($isreservation->id);
                    }
                }else{
                $reservation = new Reservation();
                $reservation->quota = $this->quota;
                $reservation->programming_id = 1;
                $reservation->user_id = $this->userId;
                $reservation->reservation_date = now();
                $reservation->save();

                $this->openModalMembers($reservation->id);
                }
            }
            //Siguiente paso

        } else {

            

            $isreservation = Reservation::where('programming_id', $this->selectedProgramming)
                ->where('user_id', $this->userId)->first();

            if ($isreservation) {
                
                $memberCount = $isreservation->member()->count();
                
                if ($memberCount >= 1 && !$this->editReservation) {
                    $this->emit('alert', 'Este usuario ya tiene una reserva para este horario');
                } else {
                    $programming = Programming::find($this->selectedProgramming);
                    $programming->quota_available =
                    ($programming->quota_available + $isreservation->quota) - $this->quota;
                    $programming->save();
                    
                    $isreservation->quota = $this->quota;
                    $isreservation->save();
                    
                    $this->openModalMembers($isreservation->id);
                }
            } else {
                $reservation = new Reservation();
                $reservation->quota = $this->quota;
                $reservation->programming_id = $this->selectedProgramming;
                $reservation->user_id = $this->userId;
                $reservation->reservation_date = now();
                $reservation->save();

                $programming = Programming::find($this->selectedProgramming);
                $programming->quota_available -= $this->quota;
                $programming->save();

                //Siguiente paso
                $this->openModalMembers($reservation->id);
            }
        }
    }

    public function openModalCreateUser()
    {$this->resetDates();
        $this->openReservation = false;
        if ($this->wait == 1) {
            $this->emitTo('create-waiting', 'open');
        } else {
            $this->emitTo('create-user', 'open');
        }
    }

    public function open($params)
    {
        $this->resetDates();
        $this->userId = $params['userId'] ?? null;
        $this->email = $params['email'] ?? null;
        $this->wait = $params['wait'] ?? null;
        $this->reservationId = $params['reservationId'] ?? null;
        $this->editReservation = $params['editReservation'] ?? null;
        $this->programmationId = $params['programmationId'] ?? null;
        $this->loadReservations();
        $this->openReservation = true;
    }

    public function openModalMembers($reservationId)
    {
        $params = [
            'reservationId' => $reservationId,
            'editReservation' => $this->editReservation,
            'programmationId' => $this->programmationId,
            'email' => strtolower($this->email),
            'wait' => $this->wait

        ];
        $this->openReservation = false;
        $this->emitTo('create-members', 'open', $params);
    }

    public function resetDates()
    {
        $this->reset(['quota', 'quotaAvailable', 'selectedProgramming', 'mensaje', 'userId',
        'reservationId','editReservation','programmationId', 'programmings']);
        
    }
}
