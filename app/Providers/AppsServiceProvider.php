<?php

namespace App\Providers;

use App\Foundation\AppsManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Livewire\Volt\Volt;

class AppsServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->classAutoloader('apps', 'Apps');
        $this->app->bind('apps', AppsManager::class, true);
    }

    public function boot()
    {
        Volt::mount([base_path()]); // Mounting the whole base folder is not ideal, but we want to have apps namespace
        View::addLocation(base_path());
        $this->discoverLivewireComponents(base_path('apps'), 'Apps');
    }

    protected function classAutoloader($directory, $namespace)
    {
        spl_autoload_register(function ($class) use ($directory, $namespace) {
            if (str_starts_with($class, $namespace)) {
                $path = str_replace("$namespace\\", '', $class);
                $parts = explode('\\', $path);
                $parts[0] = Str::kebab($parts[0]);
                $path = implode('/', $parts);
                $file = base_path("{$directory}/{$path}.php");
                if (file_exists($file)) {
                    include $file;
                }
            }
        });
    }

    private function discoverLivewireComponents($directory, $namespace)
    {
        foreach (File::allFiles($directory) as $file) {
            $class = $namespace.'\\'.str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());
            if (class_exists($class) && is_subclass_of($class, \Livewire\Component::class)) {
                $name = strtolower(str_replace('\\', '.', $class));
                Livewire::component($name, $class);
            }
        }
    }
}
