<?php

use App\Models\Device;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    #[Validate('required')]
    public $name;

    public function add()
    {
        $this->validate();
        Device::create([
            'name' => $this->name
        ]);

        return $this->redirectRoute('dashboard');
    }
}; ?>

<div>
    <flux:modal.trigger name="add-device">
        <flux:button>Add device</flux:button>
    </flux:modal.trigger>
    <flux:modal name="add-device" class="md:w-96">
        <form wire:submit="add" class="space-y-6">
            <flux:heading size="lg">Add device</flux:heading>
            <flux:input wire:model="name" label="Name" placeholder="Your name" required />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Add</flux:button>
            </div>
        </form>
    </flux:modal>
</div>