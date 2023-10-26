<?php

namespace App\Http\Livewire;

use App\Models\Profile;
use App\Models\User;
use App\Models\Reservation;
use Livewire\Component;

class Users extends Component
{
    public $title;
    protected $users;
    public $search = '';
    public $sort = 'users.name';
    public $direction = 'asc';
    public $count = 0;
    public $cant = 5;
    public $page = 1;

    public $sortReservation = 'programmings.initial_date';
    public $directionReservation = 'asc';

    public $openEditRegister = false;
    public $openReservationsUser = false;
    public $document, $name, $cell, $address, $neighborhood, $birth, $eps, $reference, $email;
    public $profile;
    public $experience2022;
    public $epsState = 0;
    public $userId;
    public $reservations;


    protected $listeners = ['confirmDelete', 'render', 'confirmDeletereservation'];

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

    public function previousPage()
    {

        $this->searchUsers();
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function nextPage()
    {
        $this->searchUsers();
        if ($this->page < $this->users->lastPage()) {
            $this->page++;
        }
        // $this->emit('consoleLog', ['pagina' => $this->page]);
    }

    public function gotoPage($page,)
    {
        $this->page = $page;
        $this->searchUsers();
    }

    public function order($sort)
    {

        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function orderReservation($sort)
    {

        if ($this->sortReservation == $sort) {
            if ($this->directionReservation == 'desc') {
                $this->directionReservation = 'asc';
            } else {
                $this->directionReservation = 'desc';
            }
        } else {
            $this->sortReservation = $sort;
            $this->directionReservation = 'asc';
        }
    }

    private function searchUsers()
    {
        try {
            $roleID = 2;

            $usersQuery = User::leftJoin('profiles', 'users.id', '=', 'profiles.user_id')

                ->whereHas('roles', function ($query) use ($roleID) {
                    $query->where('role_id', $roleID);
                })
                ->where('profiles.document', '<>', '')
                ->select('users.id', 'users.name', 'profiles.document', 'profiles.is_collaborator', 'profiles.cell')
                ->orderBy($this->sort, $this->direction);

            if (!empty($this->search)) {
                $search = '%' . $this->search . '%';
                $usersQuery->where(function ($query) use ($search) {
                    $query->orWhere('users.name', 'like', $search)
                        ->orWhere('profiles.document', 'like', $search)
                        ->orWhere('profiles.cell', 'like', $search);
                });
            }

            $this->count = $usersQuery->count();
            $this->users = $usersQuery->paginate($this->cant, ['*'], 'listPage', $this->page);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function deleteUser($userId)
    {
        $this->emit('delUser', $userId);
    }
    public function confirmDelete($userId)
    {
        $user = User::find($userId);

        // Si se encontró al usuario, elimínalo
        if ($user) {
            $user->delete();
        }
    }

    public function updatingSearch()
    {
        $this->page = 1;
    }

    public function render()
    {
        $this->searchUsers();
        $this->searchReservations();
        return view('livewire.users', [
            'users' => $this->users
        ]);
    }

    public function editUser($document)
    {
        $this->document = $document;
        $this->searchProfile();
        $this->openEditRegister = true;
    }

    public function openReservation($document, $userId)
    {
        $this->document = $document;
        $this->userId = $userId;
        $this->searchProfile();
        $this->searchReservations();
        $this->openReservationsUser = true;
    }

    public function searchReservations()
    {
        $this->reservations = Reservation::leftJoin('programmings', 'reservations.programming_id', '=', 'programmings.id')
            ->select('programmings.initial_date', 'programmings.initial_time', 'reservations.quota', 'reservations.user_id', 'reservations.id')
            ->where('user_id', $this->userId)
            ->orderBy($this->sortReservation, $this->directionReservation)
            ->get();
    }

    public function deleteReservation($reservationId)
    {
        $this->emit('delReservationUser', $reservationId);
    }

    public function confirmDeletereservation($reservationId)
    {
        $reservation = Reservation::find($reservationId);

        // Si se encontró la resrva, elimínalo
        if ($reservation) {
            $idProgramming = $reservation->programming_id;
            $reservation->delete();
            $this->emitTo('users', 'render');
            // $this->resetDatesInAll();
        }
        $this->emit('reiniciar');
    }

    public function editRegisterUser($document, $reservationId)
    {
        $reservation = Reservation::find($reservationId);
        $params = [
            'userId' => $this->userId,
            'email' => strtolower($this->email),
            'editReservation' => true,
            'programmationId' => $reservation->programming_id
        ];

        $this->emitTo('create-reservation', 'open',  $params);
         $this->openReservationsUser = false;

    }


    public function searchProfile()
    {
        $profile = Profile::where('document', $this->document)->with('user')->first();

        if ($profile) {
            $this->profile = $profile;
            // $this->document = $profile->document;
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
            } else {
                $this->epsState = 0;
            }
        }
    }

    public function saveUpdateContinue()
    {

        $this->validate();
        try {

            $profile = Profile::where('document', $this->document)->with('user')->first();
            if ($profile) {

                $user = User::find($profile->user_id);

                $user->name = $this->name;
                $user->save();

                $profile = $user->profile;

                // Actualiza los campos del perfil
                $profile->cell = $this->cell;
                $profile->address = $this->address;
                $profile->neighborhood = $this->neighborhood;
                $profile->birth = $this->birth;
                $profile->eps = $this->eps ?? '';
                $profile->reference = $this->reference ?? '';
                $profile->experience2022 = $this->experience2022 ?? false;

                // Guarda los cambios en el perfil
                $profile->save();
            }
        } catch (\Throwable $th) {
            dd($th);
        }


        $this->openEditRegister = false;
        $this->resetDatesIn();
        $this->emit('alert', 'Usuario actualizado', 'success');
    }

    public function resetDatesIn()
    {
        $this->reset(['document', 'name', 'cell', 'address', 'neighborhood', 'birth', 'email', 'profile']);
    }

    public function close()
    {
        $this->openEditRegister = false;
        $this->resetDatesIn();
    }

    public function closeReservation()
    {
        $this->openReservationsUser = false;
        $this->resetDatesIn();
    }
}
