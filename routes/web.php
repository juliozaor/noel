<?php

use App\Http\Controllers\Api\NewPasswordController;
use Illuminate\Support\Facades\Route;
use App\Mail\ContactanosMailable;
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


/* Route::get('contactanos', function(){
$correo = new ContactanosMailable;

Mail::to('juliojimmeza@gmail.com')->send($correo);
return "Mensaje enviado";
});
 */