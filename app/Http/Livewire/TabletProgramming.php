<?php

namespace App\Http\Livewire;

use App\Events\updateProgrammingEvent;
use App\Models\Programming;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class TabletProgramming extends Component
{
    use WithPagination;

    public $title;
    public $search = '';
    public $sort = 'id';
    public $direction = 'desc';
    public $programming;
    public $openEditProgramming = false;
    public $hour;
    public $count = 0;

    public $cant = '10';
    protected $queryString = [
        'cant' => ['except' => '10'],
        'sort' => ['except' => 'id'],
        'direction' => ['except' => 'desc'],
        'search' => ['except' => '']
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

        $programmingsQuery = Programming::where(function (Builder $query) {
            $query->orWhere('quota', 'like', '%' . $this->search . '%')
                ->orWhere('quota_available', 'like', '%' . $this->search . '%')
                ->orWhere('initial_date', 'like', '%' . $this->search . '%');
        });

        $this->count = $programmingsQuery->count();

        $programmings = $programmingsQuery
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->cant);






        return view('livewire.tablet-programming', compact('programmings'));
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

    public function editProgramming(Programming $programming)
    {
        $this->programming = $programming;
        $this->openEditProgramming = true;
        $this->date = date('Y-m-d', strtotime($programming->initial_date));
        $this->time = date('H:i', strtotime($programming->initial_date));
        $this->name = $programming->event->name;
        $this->detail = $programming->event->detail;
        $this->description = $programming->event->description;
        $this->eventId = $programming->event->id;
        $this->quota = $programming->quota;
        $this->state = $programming->state;
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

        $this->reset(['openEditProgramming']);
        $this->emitTo('tablet-programming', 'render');
        $this->emit('alert', 'Actualizado con éxito');
        event(new updateProgrammingEvent($this->programming->id));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
