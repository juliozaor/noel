<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\ProgrammingController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Mail\ReservationVerification;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::post('auth/register', [UserController::class, 'store'])->name('auth.register');
Route::post('auth/login', [UserController::class, 'login'])->name('auth.login');
Route::post('auth/forgot-password', [NewPasswordController::class, 'forgotPassword'])->name('auth.forgot');
Route::post('auth/reset-password', [NewPasswordController::class, 'reset'])->name('auth.reset');

Route::middleware(['auth:sanctum', 'can:api'])->group(function () {
    Route::post('auth/logout', [UserController::class, 'logout'])->name('auth.logout');
    // Members
    Route::resource('members', MemberController::class)->names('api.members')->only([
        'store'
    ]);
    Route::post(
        'members/reservation',
        [MemberController::class, 'updateMemberReservation']
    )->name('api.members.updateMembersReservation');

    // Reservations
    Route::resource('reservations', ReservationController::class)->names('api.reservations')->only([
        'store'
    ]);

    Route::post('waiting-list', [ReservationController::class, 'waitingList'])
        ->name('api.reservations.waitingList');

    Route::get(
        'reservations/user/{userId}',
        [ReservationController::class, 'perUser']
    )
        ->name('api.reservations.perUser');

    Route::get(
        'reservations/{reservationId}/members',
        [ReservationController::class, 'getMembersByReservation']
    )
        ->name('api.reservations.perReservation');

    Route::put('auth/update/{userId}', [UserController::class, 'update'])->name('auth.update');
});

Route::get(
    'reservations/confirmet/{reservation}',
    [ReservationController::class, 'confirmet']
)->name('api.reservations.confirmet');

Route::get('programmings/event', [ProgrammingController::class, 'showEvent'])->name('api.programmings.event');
Route::get(
    'programmings/updateState',
    [ProgrammingController::class, 'updateStat']
)->name('api.programmings.updateState');


Route::resource('events', EventController::class)->names('api.events')->only([
    'index'
]);


/* //Correo de prueba
Route::get('confirmar', function () {
    $correo = new ReservationVerification('155');
    Mail::to('juliojimmeza@gmail.com')->send($correo);
    return "Mensaje enviado";
});
 */