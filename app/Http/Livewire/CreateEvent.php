<?php

namespace App\Http\Livewire;

use App\Models\Event;
use Livewire\Component;

class CreateEvent extends Component
{

    public $open = false;

    public $name, $detail, $description;

    public function save() {
        $event = Event::create([
            'name'=> $this->name,
            'detail'=> $this->detail,
            'description'=> $this->description
        ]);
    }

    public function render()
    {
        return view('livewire.create-event');
    }
}
