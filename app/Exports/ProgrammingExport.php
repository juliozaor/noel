<?php

namespace App\Exports;


use App\Models\Programming;
use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProgrammingExport implements FromView
{

    public $date;
    public function __construct(string $date)
    {
        $this->date = $date;
    }
    public function view(): View
    {
        $reservations = [];
        $programmings = Programming::where('initial_date', $this->date)->where('state', 1)->get();
        if ($programmings) {
            foreach ($programmings as $programming) {
                $reserevationsSql = Reservation::where('programming_id', $programming->id)
                    ->with(['user' => function ($query) {
                        $query->with('profile');
                    }])
                    ->get();
                if ($reserevationsSql) {
                    foreach ($reserevationsSql as $reserevationSql) {
                        $destination = trim($reserevationSql->user->profile->cell ?? '');
                        if (preg_match('/^3\d{9}$/', $destination)) {
                            $reservations[] = [
                                'destination' => '57'.$destination,
                                'Fecha' => $programming->initial_date,
                                'Hora' =>$programming->initial_time
                            ];
                        }
                    }
                }
            }
        }
        return view('exports.reservations', [
            'reservations' => $reservations
        ]);
    }
}
