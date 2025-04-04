<?php

namespace App\Facades;

use App\Foundation\AppsManager;

class Apps extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return AppsManager::class;
    }
}
