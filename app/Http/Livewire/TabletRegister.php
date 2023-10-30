<?php

namespace App\Http\Livewire;

use App\Models\Programming;
use Livewire\Component;
use Livewire\WithPagination;


class TabletRegister extends Component
{
    use WithPagination;

    public $title;
    public $sort = 'id';
    public $direction = 'desc';
    public $programming;
    public $openRegisterUserInEvent = false;
    public $hour;
    public $count = 0;
    public $begin = '2023-11-01';
    public $end = '2023-12-31';
    public $all = 0;
    public $users;
    public $links;

    public $cant = '10';
    protected $queryString = [
        'cant' => ['except' => '10'],
        'sort' => ['except' => 'id'],
        'direction' => ['except' => 'desc'],
    ]; // informacion en la url

    public $name, $detail, $description, $eventId;
    public $date, $time, $quota, $state;

    protected $rules = [
        'name' => ['required', 'string'],
        'detail' => ['required', 'string', 'max:30'],
        'description' => ['required', 'string', 'max:60'],
        'date' => ['required', 'date'],
        'time' => ['required', 'date_format:H:i'],
        'quota' => ['required', 'integer'],
        'state' => ['required', 'integer'],
    ];

    protected $listeners = ['render'];

    public function render()
    {


        $programmingsQuery = Programming::where('programmings.waiting', '<>', 1);

        if ($this->all == 1) {
            $programmingsQuery->where('programmings.quota_available', '>', 0);
        } elseif ($this->all == 2) {
            $programmingsQuery->where('programmings.quota_available', '<=', 0);
        }

        $programmingsQuery->where('state', 1);
        $dateInitial = $this->begin;
        $dateEnd = $this->end;
        
        $programmingsDB = $programmingsQuery->where(function ($query) use ($dateInitial, $dateEnd) {
            $query->whereBetween('initial_date', [$dateInitial, $dateEnd]);
        })->orderBy($this->sort, $this->direction);
        
        
        $this->count = $programmingsDB->count();
            
        $programmings = $programmingsDB->paginate($this->cant, ['*'], 'registersPage');

        return view('livewire.tablet-register', compact('programmings'));
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

   
    public function registerUserInEvent(Programming $programming, $tab)
    {
        $params = [
            'programmig' => $programming,
            'tab' => $tab
        ];

        $this->emitTo('register-user-in-event', 'open',  $params);
    }

    public function update()
    {

        $this->validate();

        $programming = Programming::find($this->programming->id);
        $this->hour = date('H:i', strtotime($this->time) + 7200); // 7200 segundos = 2 horas

        $programming->update([
            'initial_date' => $this->date . ' ' . $this->time,
            'final_date' => $this->date . ' ' . $this->hour,
            'quota' => $this->quota,
            'quota_available' => $this->quota,
            'state' => $this->state
        ]);

        $programming->save();

        // $this->reset(['openEditProgramming']);
        $this->emitTo('tablet-programming', 'render');
        $this->emit('alert', 'Actualizado con éxito','success');
        $this->updateProgrammingQuota($this->programming->id);
    }

    public function updateProgrammingQuota($programmingId)
    {

        if ($programmingId <> 1) {
            $programming = Programming::findOrFail($programmingId);
            $totalReservationsQuota = $programming->reservation()->sum('quota');
            $newQuotaAvailable = $programming->quota - $totalReservationsQuota;


            $programming->update([
                'quota_available' => $newQuotaAvailable
            ]);
        }
    }

  
}
