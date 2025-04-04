<?php

namespace App;

use Livewire\Wireable;

abstract class App implements Wireable
{
    private \App\Models\App $app;

    public function __construct(private $id)
    {
        $this->app = \App\Models\App::find($id);
    }

    public function metadata(): array
    {
        return $this->app->metadata;
    }

    public function save(array $metadata): bool
    {
        $this->app->metadata = $metadata;

        return $this->app->save();
    }

    public function toLivewire()
    {
        return [$this->id];
    }

    public static function fromLivewire($value)
    {
        return new static($value[0]);
    }
}
