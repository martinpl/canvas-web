<?php

namespace Canvas\Facades;

use Canvas\Foundation\AppsManager;

class Apps extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return AppsManager::class;
    }
}
