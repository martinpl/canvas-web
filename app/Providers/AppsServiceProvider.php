<?php

namespace Canvas\Providers;

use Canvas\Foundation\AppsManager;
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
        Volt::mount([base_path('apps')]);
        View::addLocation(base_path('apps'));
        $this->resolveLivewireMissingComponent(base_path('apps'), 'Apps');
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

    private function resolveLivewireMissingComponent($directory, $namespace)
    {
        Livewire::resolveMissingComponent(function ($name) use ($namespace) {
            if (! str_contains($name, '.')) {
                return;
            }

            $class = explode('.', $name);
            $class = array_map(fn ($item) => Str::studly($item), $class);
            $class = [$namespace, ...$class];
            $class = implode('\\', $class);

            if (class_exists($class) && is_subclass_of($class, \Livewire\Component::class)) {
                return $class;
            }
        });
    }
}
