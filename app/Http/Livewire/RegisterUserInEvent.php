<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

use function Termwind\render;

class RegisterUserInEvent extends Component
{
    use WithPagination;
    protected $users;
    public $search = '';
    public $sortUser = 'users.name';
    public $directionUser = 'asc';
    public $countUser = 0;
    public $cantUser = 5;
    public $page = 1;
    public $openRegisterUserInEvent = false;
    public $programmingId;

    protected $listeners = ['open', 'changeCantUser'];

    public function previousPage()
    {
        $this->searchUsers();
        if ($this->page > 1) {
            $this->page--;
            $this->searchUsers();
        }
    }

    public function nextPage()
    {

        $this->searchUsers();
        if ($this->page < $this->users->lastPage()) {
            $this->page++;
            $this->searchUsers();
        }
    }

    public function gotoPage($page)
    {
        $this->searchUsers();
        $this->page = $page;
        $this->searchUsers();
    }

    public function updatingSearch()
    {
        $this->searchUsers();
    }
    
    public function onSearchChange()
    {
       // $this->search = $value;
        $this->searchUsers();
    }

    public function changeCantUser()
    {
        $this->searchUsers();
    }
    public function order($sortUser)
    {

        if ($this->sortUser == $sortUser) {
            if ($this->directionUser == 'desc') {
                $this->directionUser = 'asc';
            } else {
                $this->directionUser = 'desc';
            }
        } else {
            $this->sortUser = $sortUser;
            $this->directionUser = 'asc';
        }
        $this->searchUsers();
    }
    private function searchUsers()
    {

        $usersQuery = User::join('reservations', 'users.id', '=', 'reservations.user_id')
            ->join('programmings', 'reservations.programming_id', '=', 'programmings.id')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('programmings.id', $this->programmingId)
            ->where('users.id', '<>', 1)
            ->select('users.id', 'users.name', 'profiles.document', 'profiles.cell')
            ->orderBy($this->sortUser, $this->directionUser);

        if (!empty($this->search)) {
            $search = '%' . $this->search . '%';
            $usersQuery->where(function ($query) use ($search) {
                $query->orWhere('users.name', 'like', $search)
                    ->orWhere('profiles.document', 'like', $search)
                    ->orWhere('profiles.cell', 'like', $search);
            });
        }

        $this->countUser = $usersQuery->count();
        $this->users = $usersQuery->paginate($this->cantUser, ['*'], 'pageRegister', $this->page);


        $this->openRegisterUserInEvent = true;
    }


    public function render()
    {
        return view('livewire.register-user-in-event', [
            'users' => $this->users,
        ]);
    }

    public function open($params)
    {
        $this->programmingId = $params['programmigId'] ?? null;
        $this->searchUsers();
    }

    public function editRegisterUser($document)
    {
        $params = [
            'document' => $document,
            'programmationId' => $this->programmingId
        ];

        $this->emitTo('create-user', 'openEdit',  $params);
        $this->openRegisterUserInEvent = false;
    }
}
