<?php

namespace App\Http\Livewire;

use App\Models\Profile;
use App\Models\User;
use Livewire\Component;

class CreateUser extends Component
{
    public $openNewRegister = true;
    public $document, $name, $cell, $address, $neighborhood, $birth, $eps, $reference, $email;
    public $profile;
    public $experience2022;
    public $epsState = 0;

    public $user;


    public function updatedEpsState()
    {
        $this->resetValidation('eps');
    }

    protected function rules()
    {
        $rules = [
            'document' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'cell' => ['required', 'integer'],
            'address' => ['required', 'string', 'max:80'],
            'neighborhood' => ['required', 'string', 'max:60'],
            'birth' => ['required', 'date'],
            'email' => ['required', 'string', 'max:60'],
        ];

        if ($this->epsState == 1) {
            $rules['eps'] = ['required', 'string']; // Agregar la regla de validación cuando $epsState es 1
        }

        return $rules;
    }

    public function mount()
    {
        $this->experience2022 = 0;
        $this->epsState = 0;
    }


    public function searchProfile()
    {
        $this->reset(['profile']); // Resetea el valor del perfil antes de cada búsqueda
        $this->resetDates();
        $profile = Profile::where('document', $this->document)->with('user')->first();


        if ($profile) {
            $this->profile = $profile;
            $this->document = $profile->document;
            $this->name = $profile->user->name;
            $this->cell = $profile->cell;
            $this->address = $profile->address;
            $this->neighborhood = $profile->neighborhood;
            $this->birth = $profile->birth;
            $this->eps = $profile->eps;
            $this->reference = $profile->reference;
            $this->email = $profile->user->email;
            $this->experience2022 = $profile->experience2022;
            if ($this->eps != '') {
                $this->epsState = 1;
            }
        }
    }

    public function render()
    {
        return view('livewire.create-user');
    }

    public function saveUpdateContinue()
    {
        
        $this->validate();

        $user = Profile::where('document', $this->document)->with('user')->first();
        if($user){
            $this->user = $user;
            //Actualizar usuario y perfil
        }else{
           // crear usuario y perfil
        }

        /*  $event = Event::findOrFail($this->eventId);
        $this->hour = date('H:i', strtotime($this->time) + 7200); // 7200 segundos = 2 horas

        $programmingData = [
            'initial_date' => $this->date . ' ' . $this->time,
            'final_date' => $this->date . ' ' . $this->hour,
            'quota' => $this->quota,
            'quota_available' => $this->quota,
            'state' => $this->state
        ];

        $programming = new Programming($programmingData);

        // Guardar la programación asociada al evento
        $event->programming()->save($programming);
        $this->reset(['open', 'time', 'date']);
        $this->emitTo('tablet-programming', 'render');
        $this->emit('alert', 'Guardado con éxito'); */
    }

   private function resetDates(){
    $this->reset(['user', 'name', 'cell', 'address', 'neighborhood', 'birth', 'email']);
    }
}
