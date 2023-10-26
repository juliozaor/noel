<?php

namespace App\Http\Livewire;

use App\Models\Programming;
use App\Models\Reservation;
use Livewire\Component;

class InformReservations extends Component
{
    public $selectedDate;
    public $selectedTime;
    public $cuposTotales;
    public $cuposReservados;
    public $cuposConfirmados;

    public function getChartData()
    {
        // Validar que se hayan seleccionado fecha y hora
        if (!$this->selectedDate || !$this->selectedTime) {
            $this->reset(['cuposTotales', 'cuposReservados', 'cuposConfirmados']);
            session()->flash('message', 
            'No se encontro información, seleccione la fecha y hora de un evento existente');
            return;
        }

        // Calcular la fecha y hora seleccionadas
        // $selectedDateTime = "{$this->selectedDate} {$this->selectedTime}";

        // Consultar la tabla 'programmings' para obtener la cantidad de cupos totales
        $programming = Programming::where('initial_date', $this->selectedDate)
            ->where('initial_time', $this->selectedTime)->first();
        if ($programming) {
            # code...
            $cuposTotales = $programming->quota;


            $cuposReservados = $programming->reservation()->sum('quota');
            // Consultar la tabla 'reservations' para obtener la suma de cuotas de las reservas



            // Consultar la tabla 'reservations' para obtener la suma de cuotas de las reservas confirmadas
            $cuposConfirmados = Reservation::where('programming_id', $programming->id)
                ->where('confirmed', 1)
                ->sum('quota');

            // Pasar los datos a la vista
            $this->cuposTotales = $cuposTotales;
            $this->cuposReservados = $cuposReservados;
            $this->cuposConfirmados = $cuposConfirmados;


            $this->emit(
                'chartDataUpdated',
                [
                    'cuposTotales' => $this->cuposTotales,
                    'cuposReservados' => $this->cuposReservados,
                    'cuposConfirmados' => $this->cuposConfirmados
                ]
            );
        }else{
            $this->reset(['cuposTotales', 'cuposReservados', 'cuposConfirmados']);
            session()->flash('message',
            'No se encontro información, seleccione una fecha y hora de un evento existente');
           
        }
    }

    public function render()
    {
        return view('livewire.inform-reservations');
    }
}
