<?php

namespace App\Exports;


use App\Models\Programming;
use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ProgrammingExportInfo implements FromView
{

    public $date;
    public function __construct()
    {}
    public function view(): View
    {
        $report = DB::table('programmings as p')
            ->select('p.id', 'p.initial_date as fecha', 'p.initial_time as hora', 'p.quota as cupos_registrados', 'p.quota_available as cupos_disponibles')
            ->selectRaw('SUM(CASE WHEN pr.is_collaborator = 1 THEN r.quota ELSE 0 END) AS cupos_reservados_colaboradores')
            ->selectRaw('SUM(r.quota) AS cupos_reservados')
            ->selectRaw('COALESCE(q.asistencias, 0) AS asistencias')
            ->leftJoin('reservations as r', 'r.programming_id', '=', 'p.id')
            ->leftJoin('profiles as pr', 'pr.user_id', '=', 'r.user_id')
            ->leftJoin(DB::raw('(SELECT r.programming_id, COUNT(CASE WHEN q.status_qr = 0 THEN 1 END) AS asistencias
                FROM reservations r
                JOIN qr_codes q ON q.reservation_id = r.id
                GROUP BY r.programming_id) as q'), 'p.id', '=', 'q.programming_id')
            ->where('p.id', '<>', 1)
            ->groupBy('p.id', 'p.initial_date', 'p.initial_time', 'p.quota', 'p.quota_available', 'q.asistencias')
            ->orderBy('p.initial_date')
            ->get();

        // Mostrar el resultado
        return view('exports.report', [
            'reportData' => $report
        ]);
    }
}
