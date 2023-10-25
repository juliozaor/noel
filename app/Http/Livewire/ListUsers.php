<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class ListUsers extends Component
{
    protected $users;
    public $search = '';
    public $sort = 'users.name';
    public $direction = 'asc';
    public $count = 0;
    public $cant = 5;
    public $page = 1;
    
    public $programmingId;
    protected $listeners = ['programation'];
    
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
    
    public function programation($programmingId){
        $this->programmingId = $programmingId;
    }

    private function searchUsers()
    {

        $roleID = 2;
        $usersQuery = User::join('reservations', 'users.id', '=', 'reservations.user_id')
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->whereHas('roles', function ($query) use ($roleID) {
            $query->where('role_id', $roleID);
        })
        ->where('profiles.document','<>', '')
        ->where('reservations.programming_id','<>',$this->programmingId)
            ->select('users.id', 'users.name', 'profiles.document','profiles.is_collaborator', 'profiles.cell')
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

    public function editRegisterUser($document)
    {
        $params = [
            'document' => $document,
            'programmationId' => $this->programmingId,
            'editReservation' => false
        ];

        $this->emitTo('create-user', 'openEdit',  $params);
        $this->emitTo('register-user-in-event', 'close');
    }

    public function render()
    {
        $this->searchUsers();
        return view('livewire.list-users',[
            'users' => $this->users
        ]);
    }
}
