<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProgrammingExport;
use App\Http\Controllers\Controller;
use App\Mail\ExcelExportMail;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function export()
    {
        $date = date('Y-m-d');
        $fecha = new DateTime($date);
        $fecha->modify('+1 day'); // Sumar un día

        $nuevaFecha = $fecha->format('Y-m-d');
        Excel::store(new ProgrammingExport($nuevaFecha), 'reservas.csv');
        $file = Storage::disk('local')->get('reservas.csv');
        Mail::to('zaor.julio@gmail.com')->send(new ExcelExportMail($file));
        return 'Correo enviado con archivo adjunto ' . $nuevaFecha . ' | ' . date('H:i:s');
    }
}
