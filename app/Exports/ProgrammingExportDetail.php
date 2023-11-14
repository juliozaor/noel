<?php

namespace App\Exports;


use App\Models\Programming;
use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ProgrammingExportDetail implements FromView
{

    public $date;
    public function __construct()
    {}
    public function view(): View
    {
        $report =  DB::table('users as u')->select(
            'p.id as eventoId',
            'r.id as reservaId',
            'u.id as usuarioId',
            'p.initial_date as fecha',
            'p.initial_time as hora',
            'pr.document as documento_titular',
            'u.name as nombre_titular',
            'u.email as email_titular',
            'u.created_at as fecha_registro',
            'r.quota as cupos_reservados',
            'pr.cell as telefono_titular',
            DB::raw("CASE WHEN pr.is_collaborator = 1 THEN 'Si' ELSE 'No' END as EsColaborador"),
            'm.document as documento_acompanante',
            'm.name as nombre_acompanante',
            'm.is_minor as esMenor'
        )
        ->leftJoin('reservations as r', 'r.user_id', '=', 'u.id')
        ->leftJoin('profiles as pr', 'u.id', '=', 'pr.user_id')
        ->leftJoin('programmings as p', 'p.id', '=', 'r.programming_id')
        ->leftJoin('members_reservation as mr', 'r.id', '=', 'mr.reservation_id')
        ->leftJoin('members as m', 'mr.members_id', '=', 'm.id')
        ->where('p.id', '<>', 1)
        ->whereNotNull('r.id')
        ->orderBy('p.initial_date')
        ->get();

        // Mostrar el resultado
        return view('exports.reportDetail', [
            'reportData' => $report
        ]);
    }
}
