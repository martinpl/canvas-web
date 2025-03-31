<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/', 'dashboard')->name('dashboard');
    Volt::route('/logs', 'logs')->name('logs');
});

require __DIR__.'/auth.php';
