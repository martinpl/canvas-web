<?php

namespace Canvas\Foundation;

use Canvas\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AppsManager
{
    protected $apps = [];

    public function __construct()
    {
        $this->loadApps();
    }

    protected function loadApps()
    {
        foreach (File::directories(base_path('apps')) as $folder) {
            $appNamespace = Str::studly(basename($folder));
            $class = "Apps\\{$appNamespace}\\{$appNamespace}";
            if (class_exists($class) && is_subclass_of($class, App::class)) {
                $key = basename($folder);
                $this->apps[$key] = Str::headline($key);
            }
        }
    }

    public function list()
    {
        return $this->apps;
    }
}
