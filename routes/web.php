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

    Route::get('read_guest', function () {
        $guestAccess = new GuestAccess();
        $guestAccess->save();
        $numeroDeVisitas = GuestAccess::count();
        return view('admin.events.guestcounter',compact('numeroDeVisitas'));
        return response()->json([
            'status' => false,
            'message' =>  'Ingreso del invitado existoso'
        ], 200); 
    });

   
});

/* Route::post('auth/update', [NewPasswordController::class, 'update'])->name('auth.update.pass'); */
Route::post('auth/update', [NewPasswordController::class, 'update'])->name('auth.update.pass');

