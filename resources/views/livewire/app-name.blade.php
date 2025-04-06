<?php

use App\Models\Device;
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
    <h2 x-show="!edit" @click="edit = true; $nextTick(() => {
        $refs.input.focus();
        $refs.input.setSelectionRange($refs.input.value.length, $refs.input.value.length);
    })">
        {{ $app->name }}
        {{-- TODO: Add pencil icon when x-icon will not be buggged --}}
    </h2>
    <form wire:submit="save; edit = false" x-show="edit" x-cloak>
        <input type="text" wire:model.fill="name" value="{{ $app->name }}" x-ref="input" />
        <flux:button type="submit">Save</flux:button>
    </form>
</div>