<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\EventController;

Route::middleware('can:administrador')->group(function () {
  
  Route::get('', [HomeController::class, 'index']);
  Route::get('events/register', [EventController::class, 'indexRegister'])
  ->name('admin.events.register');
  Route::get('events/users', [EventController::class, 'users'])->name('admin.events.users');
  
  Route::resource('events', EventController::class)->names('admin.events')->only([
    'index','create','show','edit'
  ]);
});



