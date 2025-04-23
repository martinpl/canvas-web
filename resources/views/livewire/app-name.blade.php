<?php

use Canvas\Models\Device;
use Livewire\Volt\Component;

new class extends Component
{
    public $app;

    public $name;

    public function save() {
        $this->app->name = $this->name;
        $this->app->save();
    }

}; ?>

<div x-data="{ edit: false }">
    <div class="flex gap-4" x-show="!edit" @click="edit = true; $nextTick(() => {
        $refs.input.focus();
        $refs.input.setSelectionRange($refs.input.value.length, $refs.input.value.length);
    })">
        <h2>
            {{ $app->name }}
        </h2>
        <x-icon.pencil />
    </div>
    <form wire:submit="save; edit = false" x-show="edit" x-cloak>
        <input type="text" wire:model.fill="name" value="{{ $app->name }}" x-ref="input" />
        <flux:button type="submit">Save</flux:button>
    </form>
</div>