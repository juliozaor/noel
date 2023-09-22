<?php

namespace App\Http\Livewire;

use App\Events\updateProgrammingEvent;
use App\Models\Programming;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
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
    public $begin = '2023-08-01';
    public $end = '2024-03-20';
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
            $programmingsQuery->where('quota', '>', 0);
        } elseif ($this->all == 2) {
            $programmingsQuery->where('quota', '<=', 0);
        }

        $programmingsQuery->where('state', 1);
        $dateInitial = $this->begin;
        $dateEnd = $this->end;

        $programmings = $programmingsQuery->where(function ($query) use ($dateInitial, $dateEnd) {
            $query->whereBetween('initial_date', [$dateInitial, $dateEnd]);
        })->orderBy($this->sort, $this->direction)
            ->paginate($this->cant, ['*'], 'commentsPage');

        $this->count = $programmings->count();



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

   
    public function registerUserInEvent(Programming $programming)
    {

        $params = [
            'programmigId' => $programming->id
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
        $this->emit('alert', 'Actualizado con éxito');
        event(new updateProgrammingEvent($this->programming->id));
    }

  
}
