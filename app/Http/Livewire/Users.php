<?php

namespace App\Http\Livewire;

use App\Models\User;
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

    protected $listeners = ['confirmDelete'];

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

    private function searchUsers()
    {
       

        $usersQuery = User::leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('users.id', '<>', 1)
            ->select('users.id', 'users.name', 'profiles.document', 'profiles.cell')
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
        $this->page =1;
    }

    public function render()
    {
        $this->searchUsers();
        return view('livewire.users', [
            'users' => $this->users
        ]);
    }
}
