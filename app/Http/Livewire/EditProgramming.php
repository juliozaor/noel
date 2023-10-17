<?php

namespace App\Http\Livewire;

use App\Events\updateProgrammingEvent;
use App\Models\Programming;
use Livewire\Component;

class EditProgramming extends Component
{
    public $programming;
    public $openEdit = false;
    public $hour;

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
    
    public function mount(Programming $programming)
    {
        $this->programming = $programming;
        $this->date = date('Y-m-d', strtotime($programming->initial_date));
        $this->time = date('H:i', strtotime($programming->initial_date));
        $this->name = $programming->event->name;
       /*  $this->detail = $programming->event->detail;
        $this->description = $programming->event->description; */
        $this->eventId = $programming->event->id;
        $this->quota = $programming->quota;
        $this->state = $programming->state;
    }

    public function update()
    {
        $this->validate();

        $programming = Programming::find($this->programming->id);
       $this->hour = date('H:i', strtotime($this->time ) + 7200); // 7200 segundos = 2 horas

       $programming->update([
        'initial_date' => $this->date. ' '.$this->time,
        'initial_time' => $this->time,
           // 'final_date' => $this->date . ' ' . $this->hour,
        'quota' => $this->quota,
        'quota_available'=> $this->quota,
        'state'=> $this->state
    ]);

    $programming->save();

        $this->reset(['openEdit']);
        $this->emitTo('tablet-programming','render');
        $this->emit('alert', 'Actualizado con éxito','success');
        event(new updateProgrammingEvent($this->programming->id));
    } 

    public function render()
    {
        return view('livewire.edit-programming');
    }
}
