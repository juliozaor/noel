<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProgrammingExport;
use App\Http\Controllers\Controller;
use App\Mail\ExcelExportMail;
use App\Models\EmailsAdmins;
use App\Models\Programming;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\text;

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
                $programmings = Programming::where('initial_date', $nuevaFecha)->where('state', 1)->get();
                if (count($programmings) == 0) {
                    return 'No hay programaciones para la fecha';
                }
                $storeExcel = Excel::store(new ProgrammingExport($nuevaFecha), 'Plantilla_HSM.xlsx');
                $file = Storage::disk('public')->get('Plantilla_HSM.xlsx');
                $emailBody=new ExcelExportMail($file, $nuevaFecha);
                foreach ($emails as $element) {
                    Mail::to($element->email)->send($emailBody);
                }
                
                return 'Correo enviado con archivo adjunto ';
            }else{
                return 'No hay correos para enviar';
            }
        } catch (\Throwable $th) {
            return 'Error al enviar el correo';
        }
    }

    public function download(Request $request)
    {
        try{
            if($request->date){
                $date = trim($request->date);
                $fecha = new DateTime($date);
            }else{
                $date = date('Y-m-d');
                $fecha = new DateTime($date);
                $fecha->modify('+1 day'); // Sumar un día
            }
            $nuevaFecha = $fecha->format('Y-m-d');
            return Excel::download(new ProgrammingExport($nuevaFecha), "reservas_$nuevaFecha.xlsx");
        }catch(\Throwable $th){
            return 'Error al descargar el archivo';
        }
        
    }
}
