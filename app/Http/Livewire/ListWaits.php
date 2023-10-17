<?php

namespace App\Http\Livewire;

use App\Events\updateReservationEvent;
use App\Models\Reservation;
use App\Models\User;
use Livewire\Component;

class ListWaits extends Component
{
    protected $users;
    public $search = '';
    public $sort = 'users.name';
    public $direction = 'asc';
    public $count = 0;
    public $cant = 5;
    public $page = 1;
    public $addUser = [];
    public $totalQuota = 0;

    public $quotaAvailable;
    public $programmingId;

    protected $listeners = ['available'];
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
    }

    public function gotoPage($page,)
    {
        $this->page = $page;
        $this->searchUsers();
    }
    public function updatingSearch()
    {
        $this->page =1;
    }

    public function available($params)
    {
        $this->programmingId = $params['programmationId'] ?? null;
        $this->quotaAvailable = $params['quotaAvailable'] ?? null;
    }

    public function updateUserQuotas()
    {
        $totalQuota = 0;

        foreach ($this->addUser as $key => $value) {
            if ($value) {
                $reservation = Reservation::find($key);
                if ($reservation) {
                    $totalQuota += $reservation->quota;
                }
            }
        }

        $this->totalQuota = $totalQuota;
    }

    private function searchUsers()
    {
        $roleID = 2;
        $usersQuery = User::join('reservations', 'users.id', '=', 'reservations.user_id')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('reservations.programming_id', 1)
            ->whereHas('roles', function ($query) use ($roleID) {
                $query->where('role_id', $roleID);
            })
            ->select(
                'reservations.reservation_date',
                'profiles.document',
                'users.name',
                'reservations.id',
                'reservations.quota',
                'profiles.cell'
            )
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
        $this->users = $usersQuery->paginate($this->cant, ['*'], 'waitPage', $this->page);
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

    public function registerQuota()
    {

        if (count($this->addUser) <> 0) {
            if ($this->totalQuota <= $this->quotaAvailable) {
                foreach ($this->addUser as $key => $value) {
                    if ($value) {
                        $reservation = Reservation::findOrFail($key);
                        if ($reservation) {
                            $reservation->programming_id = $this->programmingId;
                            $reservation->save();
                            event(new updateReservationEvent($reservation->id));
                        }
                        
                        $this->emitTo('tablet-register', 'render');
                        $this->emit('alert', 'Las reservas fueron asignadas con éxito','success');

                    }
                }
            } else {
                dd("no hay disponible");
            }
        } else {
            dd("no ha seleccionado");
        }
    }

    public function editRegisterWait($document, $id)
    {
        $params = [
            'document' => $document,
            'programmationId' => 1,
            'reservationId' => $id
        ];

        $this->emitTo('create-waiting', 'openEdit',  $params);
        $this->emitTo('register-user-in-event', 'close');
    }

    public function render()
    {
        $this->searchUsers();
        return view('livewire.list-waits', [
            'users' => $this->users
        ]);
    }
}
