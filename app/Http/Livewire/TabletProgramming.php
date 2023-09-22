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
    public $begin = '2023-08-01';
    public $end = '2024-03-20';
    public $all = 0;

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
        /* 'detail' => ['required', 'string', 'max:30'],
        'description' => ['required', 'string', 'max:60'], */
        'date' => ['required', 'date'],
        'time' => ['required', 'date_format:H:i'],
        'quota' => ['required', 'integer'],
        'state' => ['required', 'integer'],
    ];

    protected $listeners = ['render'];

    public function render()
    {

       
        $programmingsQuery = Programming::where('programmings.waiting','<>',1);
        
        if ($this->all == 1) {
            $programmingsQuery->where('quota', '>', 0);
        } elseif ($this->all == 2) {
            $programmingsQuery->where('quota', '<=', 0);
        }
        $dateInitial= $this->begin;
        $dateEnd= $this->end;
        
        $programmings = $programmingsQuery->where(function ($query) use ($dateInitial, $dateEnd) {
            $query->whereBetween('initial_date', [$dateInitial, $dateEnd]);
        })->orderBy($this->sort, $this->direction)
        ->paginate($this->cant);

        $this->count = $programmings->count();

 
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

   
}
