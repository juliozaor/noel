<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ExportController;

Route::middleware(['auth:sanctum','can:administrador'])->group(function () {

  Route::get('', [HomeController::class, 'index']);
  Route::get('events/register', [EventController::class, 'indexRegister'])
    ->name('admin.events.register');
  Route::get('events/users', [EventController::class, 'users'])->name('admin.events.users');
  Route::get('events/informs', [EventController::class, 'inform'])->name('admin.events.inform');
  Route::get('events/read_qr', [EventController::class, 'readQrWithImages'])->name('admin.events.readqr');
  Route::get('events/qr/{token}', [EventController::class, 'readQr'])->name('admin.events.qr');

  Route::resource('events', EventController::class)->names('admin.events')->only(['index', 'create', 'show', 'edit']);

  Route::get('sendEmailReport', [ExportController::class, 'sendEmail'])->name('admin.sendReport');
  Route::get('downloadReport', [ExportController::class, 'downloadReport'])->name('admin.downloadReport');
  Route::get('downloadInform', [ExportController::class, 'downloadInform'])->name('admin.downloadInform');
  Route::get('downloadDetail', [ExportController::class, 'downloadDetail'])->name('admin.downloadDetail');
});
