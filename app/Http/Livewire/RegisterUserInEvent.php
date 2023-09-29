<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

use function Termwind\render;

class RegisterUserInEvent extends Component
{
    use WithPagination;
    protected $usersWait;
    public $openRegisterUserInEvent = false;
    public $programmingId;
    public $tab;

    public $date, $time, $quota, $quotaAvailable;

    protected $listeners = ['open','close'];


    public function render()
    {
        return view('livewire.register-user-in-event');
    }

    public function close()
    {
        $this->openRegisterUserInEvent = false;
    }


    public function open($params)
    {
        
        $programming = $params['programmig'] ?? null;
        if ($programming) {
            $this->programmingId = $programming['id'];
            $this->date = $programming['initial_date'];
            $this->time = $programming['initial_time'];
            $this->quota = $programming['quota'];
            $this->quotaAvailable = $programming['quota_available'];
            $this->emitTo('list-users-register', 'programation',  $programming['id']);
            $paramsWait = [
                'quotaAvailable' => $programming['quota_available'],
                'programmationId' => $this->programmingId
            ];
            $this->emitTo('list-waits', 'available',  $paramsWait);
            $this->emitTo('list-users', 'programation',  $programming['id']);
        }
        $this->tab = $params['tab'] ?? null;
        if ($this->tab) {
            $this->emit('modalOpened', '#pills-' . $this->tab);
        }
        $this->openRegisterUserInEvent = true;
    }

  
}
