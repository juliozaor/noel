<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProgrammingExport;
use App\Exports\ProgrammingExportInfo;
use App\Exports\ProgrammingExportDetail;
use App\Exports\ProgrammingExportWaitList;
use App\Http\Controllers\Controller;
use App\Mail\ExcelExportMail;
use App\Mail\ExcelExportMailWaitList;
use App\Models\EmailsAdmins;
use App\Models\Programming;
use App\Models\Reservation;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\text;

class ExportController extends Controller
{
    public function sendEmail()
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

    public function downloadReport(Request $request)
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
    public function downloadInform()
    {
        try{
            return Excel::download(new ProgrammingExportInfo(), "ExperienciaNavidadEsNoel.xlsx");
        }catch(\Throwable $th){
            return 'Error al descargar el archivo';
        }
    }
    public function sendEmailWaitList()
    {
        try {
            $date = date('Y-m-d');
            $fecha = new DateTime($date);
            $nuevaFecha = $fecha->format('Y-m-d');
            $emails = EmailsAdmins::where('status', 1)->select('email')->get();
            if ($emails) {
                $reservations = Reservation::where('programming_id', 1)->where('state', 1)->get();
                if (count($reservations) == 0) {
                    return 'No hay lista de espera para la fecha';
                }
                $storeExcel = Excel::store(new ProgrammingExportWaitList(), 'ListaEspera_HSM.xlsx');
                $file = Storage::disk('public')->get('ListaEspera_HSM.xlsx');
                $emailBody = new ExcelExportMailWaitList($file, $nuevaFecha);
                foreach ($emails as $element) {
                    Mail::to($element->email)->send($emailBody);
                }

                return 'Correo enviado con archivo adjunto ';
            } else {
                return 'No hay correos para enviar';
            }
        } catch (\Throwable $th) {
            return 'Error al enviar el correo';
        }
    }
    public function downloadDetail()
    {
        try{
            return Excel::download(new ProgrammingExportDetail(), "ExperienciaNavidadEsNoel_detalle.xlsx");
        }catch(\Throwable $th){
            return 'Error al descargar el archivo';
        }
    }
}
