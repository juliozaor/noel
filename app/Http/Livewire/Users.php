<?php

namespace App\Http\Livewire;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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

    public $openEditRegister = false;
    public $document, $name, $cell, $address, $neighborhood, $birth, $eps, $reference, $email;
    public $profile;
    public $experience2022;
    public $epsState = 0;

    protected $listeners = ['confirmDelete','render'];

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

    private function searchUsers()
    {


        /*   $usersQuery = User::leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('users.id', '<>', 1)
            ->select('users.id', 'users.name', 'profiles.document', 'profiles.cell')
            ->orderBy($this->sort, $this->direction); */
        $roleID = 2;

        $usersQuery = User::leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->whereHas('roles', function ($query) use ($roleID) {
                $query->where('role_id', $roleID);
            })
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
        $this->page = 1;
    }

    public function render()
    {
        $this->searchUsers();
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
    $appUrl = config('app.url').'/api/auth';

    $profile = Profile::where('document', $this->document)->with('user')->first();
    if ($profile) {
        $user = User::find($profile->user_id);
        $apiUrl = $appUrl . '/update/' . $user->id;

        $token = $user->createToken('Authorization')->plainTextToken;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put($apiUrl, [
            "name" => $this->name,
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
    }
} catch (\Throwable $th) {
    dd($th);
}
      

        $this->openEditRegister = false;
        $this->resetDatesIn();
        $this->emit('alert', 'Usuario actualizado','success');
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
}
