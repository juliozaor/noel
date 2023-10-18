<?php

namespace App\Http\Livewire;

use App\Models\Collaborators;
use App\Models\Profile;
use App\Models\User;
use App\Models\ValidateUsers;
use App\Models\Validations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class CreateWaiting extends Component
{
    public $openNewRegister = false;
    public $document, $name, $cell, $address, $neighborhood, $birth, $eps, $reference, $email;
    public $profile;
    public $userId;
    public $experience2022;
    public $epsState = 0;
    public $wait = 1;
    public $editReservation = false;
    public $reservationId;

    protected $listeners = ['open','resetDates','openEdit','openResrv'];

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
            'birth' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
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
        $this->resetDatesIn();
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
            }else{
                $this->epsState = 0;
            }
        }
    }

    public function render()
    {
        return view('livewire.create-waiting');
    }

    public function saveUpdateContinue()
    {

        $this->validate();

        $collaborator = Collaborators::where('document', $this->document)->first();

        $validation = ValidateUsers::findOrFail(1);

        if ($validation && $validation->status == 0) {
            if (!$collaborator) {
                session()->flash('message', 'En el momento no está habilitado el registro para este usuario');
                return;
            }
        }

        $appUrl = env('APP_URL') . '/api/auth';

        $profile = Profile::where('document', $this->document)->with('user')->first();
        if ($profile) {
            $this->userId = $profile->user_id;
            $user = User::find($profile->user_id);

            $apiUrl = $appUrl . '/update/' . $user->id;

            $token = $user->createToken('Authorization')->plainTextToken;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->put($apiUrl, [
                "name" => $this->name,
                'document' => $this->document,
                'cell' => $this->cell,
                'address' => $this->address,
                'neighborhood' => $this->neighborhood,
                'birth' => $this->birth,
                'eps' => $this->eps ?? '',
                'reference' => $this->reference ?? '',
                'experience2022' => $this->experience2022 ?? false
            ]);

            // Verificar la respuesta de la API
            if ($response->successful()) {
                // La solicitud fue exitosa, puedes manejar la respuesta aquí
                $data = $response->json(); // Convierte la respuesta JSON en un array
                //return $data;
            }
        } else {

            $apiUrl = $appUrl . '/register';
            $response = Http::post($apiUrl, [
                "name" => $this->name,
                "email" => strtolower($this->email),
                "password" => $this->document,
                'document' => $this->document,
                'cell' => $this->cell,
                'address' => $this->address,
                'neighborhood' => $this->neighborhood,
                'birth' => $this->birth,
                'eps' => $this->eps ?? '',
                'reference' => $this->reference ?? '',
                'experience2022' => $this->experience2022 ?? false
            ]);

            // Verificar la respuesta de la API
            if ($response->successful()) {
                $data = $response->json();
               // dd($data['data']['id']);
                $this->userId = $data['data']['id'];
            }
        }

        //Siguiente paso

        $this->openModalreservation();

    }
   
    public function resetDatesIn()
    {
        $this->reset(['name', 'cell', 'address', 'neighborhood', 'birth', 'email', 'eps']);
    }

    public function resetDates()
    {
        $this->reset(['document','name', 'cell', 'address', 'neighborhood', 'birth', 'email', 'eps', 'editReservation']);
    }

    public function openModalreservation()
    {
       
        $params = [
            'userId' => $this->userId,
            'email' => strtolower($this->email),
            'wait' => $this->wait,
            'editReservation' => $this->editReservation,
            'programmationId' => 1,
            'reservationId' => $this->reservationId
        ];

        $this->openNewRegister = false;
        $this->emitTo('create-reservation', 'open',  $params);

    }

    public function open()
    {
        $this->openNewRegister = true;
    }

    public function openResrv($params)
    {
        $this->reservationId = $params['reservationId'] ?? null;
        $this->openNewRegister = true;
    }

    // edicion
    public function openEdit($params)
    {
        $this->document = $params['document'] ?? null;
        $this->reservationId = $params['reservationId'] ?? null;
        $this->editReservation = $params['editReservation']??true;
        $this->searchProfile();
        $this->openNewRegister = true;

    }

    public function close(){
        $this->openNewRegister = false;
        $this->resetDates();
       // $this->emit('resetDates');
    }
}
