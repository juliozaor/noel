<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProgrammingExport;
use App\Http\Controllers\Controller;
use App\Mail\ExcelExportMail;
use App\Models\EmailsAdmins;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function export()
    {
        try {
            $emails = EmailsAdmins::where('status', 1)->select('email')->get();
            if ($emails) {
                $date = date('Y-m-d');
                $fecha = new DateTime($date);
                $fecha->modify('+1 day');
                $nuevaFecha = $fecha->format('Y-m-d');
                Excel::store(new ProgrammingExport($nuevaFecha), 'reservas.xlsx');
                $file = Storage::disk('local')->get('reservas.xlsx');

                foreach ($emails as $element) {
                    Mail::to($element->email)->send(new ExcelExportMail($file, $nuevaFecha));
                }
                
                return 'Correo enviado con archivo adjunto ';
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function download()
    {
        $date = date('Y-m-d');
        $fecha = new DateTime($date);
        $fecha->modify('+1 day'); // Sumar un día

        $nuevaFecha = $fecha->format('Y-m-d');
        return Excel::download(new ProgrammingExport($nuevaFecha), 'reservas.xlsx');
    }
}
