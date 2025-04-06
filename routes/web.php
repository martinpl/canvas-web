<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/', 'dashboard')->name('dashboard');
    Volt::route('/apps', 'apps')->name('apps');
    Route::get('/apps/{id}', App\Http\Controllers\AppController::class)->name('app');
    Volt::route('/device/{id}', 'device')->name('device');
    Volt::route('/logs', 'logs')->name('logs');
    Route::get('/private/{path}', App\Http\Controllers\PrivateFileController::class)->where('path', '.*');
});

Route::any('/device/info/{id}/{key}', \App\Http\Controllers\DeviceInfoController::class)
    ->name('device.info');

Route::get('/device/image/{id}/{key}', \App\Http\Controllers\DeviceImageController::class)
    ->name('device.image');

require __DIR__.'/auth.php';
