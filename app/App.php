<?php

namespace Canvas;

use Livewire\Wireable;

abstract class App implements Wireable
{
    private \Canvas\Models\App $app;

    public function __construct(private $id)
    {
        $this->app = \Canvas\Models\App::find($id);
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
