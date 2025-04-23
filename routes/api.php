<?php

use Illuminate\Support\Facades\Route;

Route::any('/device/info/{id}/{key}', \Canvas\Http\Controllers\DeviceInfoController::class)
    ->name('device.info');

Route::get('/device/image/{id}/{key}', \Canvas\Http\Controllers\DeviceImageController::class)
    ->name('device.image');
