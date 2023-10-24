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
        $programmings = Programming::where('initial_date', $this->date)->where('state',1)->get();

        if ($programmings) {
            foreach ($programmings as $programming) {
                $reserevationsSql = Reservation::where('programming_id', $programming->id)
                    ->with('member')
                    ->with(['user' => function ($query) {
                        $query->with('profile');
                    }])
                    ->get();

                if ($reserevationsSql) {
                    foreach ($reserevationsSql as $reserevationSql) {
                        $reservations[] = [
                            'initial_date' => $programming->initial_date,
                            'initial_time' => $programming->initial_time,
                            'reservation_id' => $reserevationSql->id,
                            'name_user' => $reserevationSql->user->name,
                            'document_user' => $reserevationSql->user->profile->document,
                            'email' => $reserevationSql->user->email,
                            'cell' => $reserevationSql->user->profile->cell,
                            'name_member' => '',
                            'document_member' => '',
                            'type' => 'Titular'
                            
                        ];

                        foreach ($reserevationSql->member as $member) {
                            $reservations[] = [
                                'initial_date' => $programming->initial_date,
                                'initial_time' => $programming->initial_time,
                                'reservation_id' => $reserevationSql->id,
                                'name_user' => $reserevationSql->user->name,
                                'document_user' => $reserevationSql->user->profile->document,
                                'email' => '',
                                'cell' => '',
                                'name_member' => $member->name,
                                'document_member' =>  $member->document,
                                'type' => 'Miembro'
                                
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
