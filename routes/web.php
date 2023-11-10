<?php

use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Api\NewPasswordController;
use Illuminate\Support\Facades\Route;
use App\Mail\ContactanosMailable;
use App\Models\GuestAccess;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/bienvenido', function () {
    return view('welcome');
}); */

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/', function () {
        return view('admin.events.index');
    })->name('dashboard');
});

/* Route::post('auth/update', [NewPasswordController::class, 'update'])->name('auth.update.pass'); */
Route::post('auth/update', [NewPasswordController::class, 'update'])->name('auth.update.pass');

//crea una ruta que pueda ser accedido por el metodo get y que escriba cada que es consultado en el navegador un registro en el modelo de visitas
Route::get('read_guest', function () {
    $guestAccess = new GuestAccess();
    $guestAccess->save();
    return response()->json([
        'status' => false,
        'message' =>  'Ingreso del invitado existoso'
    ], 200); 
});

/* Route::get('contactanos', function(){
$correo = new ContactanosMailable;

Mail::to('juliojimmeza@gmail.com')->send($correo);
return "Mensaje enviado";
});
 */