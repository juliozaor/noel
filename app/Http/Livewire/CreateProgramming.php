<?php

namespace App\Http\Livewire;

use App\Models\Event;
use App\Models\Programming;
use Livewire\Component;

class CreateProgramming extends Component
{
    public $open = false;
    public $status = 1;

    public $name, $detail, $description, $eventId;
    public $date, $time, $quota, $state;
    public $hour;

    protected $rules = [
        'name' => ['required', 'string'],
       /*  'detail' => ['required', 'string', 'max:30'],
        'description' => ['required', 'string', 'max:60'], */
        'date' => ['required', 'date'],
        'time' => ['required', 'date_format:H:i'],
        'quota' => ['required', 'integer'],
        'state' => ['required', 'integer'],
    ];

    public function mount()
    {
        $this->state = 1;
    }

    public function save()
    {
        $this->validate();


        $event = Event::findOrFail($this->eventId);
        $this->hour = date('H:i', strtotime($this->time) + 7200); // 7200 segundos = 2 horas

        $programmingData = [
            'initial_date' => $this->date,
            'initial_time' => $this->time,
            'quota' => $this->quota,
            'quota_available' => $this->quota,
            'state' => $this->state
        ];

        $programming = new Programming($programmingData);

        // Guardar la programación asociada al evento
        $event->programming()->save($programming);
        $this->reset(['open', 'time', 'date']);
        $this->emitTo('tablet-programming', 'render');
        $this->emit('alert', 'Guardado con éxito');
    }

    public function render()
    {
        $event = Event::findOrFail(1);
        $this->name = $event->name;
      /*   $this->detail = $event->detail;
        $this->description = $event->description; */
        $this->eventId = $event->id;
        return view('livewire.create-programming');
    }
}
