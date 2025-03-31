<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/', 'dashboard')->name('dashboard');
});

require __DIR__.'/auth.php';
