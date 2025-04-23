<?php

use Canvas\Models\App;
use Canvas\Facades\Apps;
use Livewire\Volt\Component;

new class extends Component
{
    public $device;

    public function add($key)
    {
        App::create([
            'name' => $key,
            'app' => $key,
        ]);
    }
}; ?>

<div>
    <flux:modal.trigger name="add-app">
        <flux:button>
            Add
        </flux:button>
    </flux:modal.trigger>

    @foreach(App::all() as $app)
        <a href="{{ route('app', $app->id) }}">
            {{ $app->name }}
        </a>
    @endforeach

    <flux:modal name="add-app" class="md:w-96">
        <div class="space-y-6">
            @foreach(Apps::list() as $key => $app)
                <div>
                    {{ $key }}
                    <flux:button wire:click="add('{{ $key }}')">
                        Add
                    </flux:button>
                </div>
            @endforeach
        </div>
    </flux:modal>
</div>