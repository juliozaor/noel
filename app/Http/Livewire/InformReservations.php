<?php

namespace App\Http\Livewire;

use App\Models\Profile;
use App\Models\Programming;
use App\Models\QrCodes;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InformReservations extends Component
{
    public $selectedDate;
    public $estadisticasDia;
    public $estadisticasMes;
    public $estadisticasTotal;

    public function getChartData()
    {
        // Validar que se hayan seleccionado fecha 
        if (!$this->selectedDate) {
            $this->reset([
                'estadisticasMes',
                'estadisticasDia'
            ]);
            session()->flash(
                'message',
                'No se encontro información, seleccione la fecha de un evento existente'
            );
            return;
        }
        //get month from $this->selectedDate
        $month = date('m', strtotime($this->selectedDate));
        // consultar la tabla programing y adicionar retornar cuando cupos han reservado y cuantos qr han asistido
        $allProgrammingbyDay = Programming::withCount(['reservation','qrCodes' => function ($query) {$query->where('status_qr', 0);}])
                                            ->withSum('reservation', 'quota')
                                            ->where('initial_date', $this->selectedDate)->get();
        $monthStats = [
            'Mes' =>$month,
            'Cupos_Disponibles' => Programming::where('id', '<>', 1)
                ->whereMonth('initial_date', '=', $month)
                ->sum('quota'),
            'Personas_Registradas' => Profile::whereMonth('created_at', '<=',  $month)
                ->count(),
            'Reservaciones' => Reservation::whereMonth('created_at', '<=',  $month)->where('programming_id', '<>', 1)
                ->count(),
            'Cupos_Reservados' => Reservation::whereMonth('created_at', '<=',  $month)->where('programming_id', '<>', 1)
                ->sum('quota'),
            'Lista_Espera'=> Reservation::whereMonth('created_at', '<=',  $month)->where('programming_id', 1)
                ->count(),
            'Asistencias' => QrCodes::where('status_qr', 0)
                ->whereMonth('updated_at', '=',  $month)
                ->count(),
        ];
        if ($allProgrammingbyDay) {
            // Pasar los datos a la vista
            $this->estadisticasMes = $monthStats;
            $this->estadisticasDia = $allProgrammingbyDay;
            $this->emit(
                'chartDataUpdated',
                [
                    'estadisticasMes' => $this->estadisticasMes,
                    'estadisticasDia' => $this->estadisticasDia
                ]
            );
        } else {
            $this->reset([
                'estadisticasDia',
                'estadisticasMes'
            ]);
            session()->flash(
                'message',
                'No se encontro información, seleccione una fecha y hora de un evento existente'
            );
        }
    }
    public function render()
    {
        $this->selectedDate = !$this->selectedDate ? date('Y-m-d') : $this->selectedDate;
        return view('livewire.inform-reservations');
    }
}
