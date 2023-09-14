<?php

namespace App\Http\Livewire;

use App\Models\Programming;
use App\Models\Reservation;
use Livewire\Component;

class CreateReservation extends Component
{
    public $openReservation = false;
    public $programmings;
    public $quota;
    public $quotaAvailable;
    public $selectedProgramming;
    public $mensaje = '';
    public $userId;
    public $email;
    protected $listeners = ['open', 'resetDates'];


    protected function rules()
    {
        $rules = [
            'quota' => ['required', 'integer'],
        ];

        // Verifica la condición y agrega una regla condicional
        $rules['quotaAvailable'] = ['required', 'integer'];
        $rules['quota'] = [
            'required',
            'integer',
            function ($attribute, $value, $fail) {
                // Aquí verifica la condición personalizada
                if ($this->quotaAvailable <= $value) {
                    $fail("Has superado la cuota disponible.");
                }
            },
        ];

        return $rules;
    }

    public function mount()
    {
        $this->programmings = Programming::where('quota_available', '>=', 1)
            ->get();

        $this->quotaAvailable = $this->programmings[0]->quota_available;
        $this->selectedProgramming =  $this->programmings[0]->id;
    }

    public function render()
    {
        return view('livewire.create-reservation');
    }

    public function updatedSelectedProgramming($value)
    {
        $programming = $this->programmings->find($value);
        $this->quotaAvailable = $programming->quota_available;
    }

    public function save()
    {

        $this->validate();

        $isreservation = Reservation::where('programming_id', $this->selectedProgramming)
            ->where('user_id', $this->userId)->first();

        if ($isreservation) {

            $memberCount = $isreservation->member()->count();

            if ($memberCount >= 1) {

                $this->emit('alert', 'Este usuario ya tiene una reserva para este horario');
            }else{
                
                $programming = Programming::find($this->selectedProgramming);
                $programming->quota_available =  ($programming->quota_available + $isreservation->quota) - $this->quota;
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

    public function openModalCreateUser()
    {
        $this->openReservation = false;
        $this->emitTo('create-user', 'open');
    }

    public function open($params)
    {
        $this->userId = $params['userId'];
        $this->email = $params['email'];
        $this->openReservation = true;
    }

    public function openModalMembers($reservationId)
    {
        $params = [
            'reservationId' => $reservationId,
            'email' => strtolower($this->email),
        ];
        $this->openReservation = false;
        $this->emitTo('create-members', 'open', $params);
    }

    private function resetDates()
    {
        $this->reset(['programmings', 'quota', 'quotaAvailable', 'selectedProgramming', 'mensaje', 'userId']);
    }
}
