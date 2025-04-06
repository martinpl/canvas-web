<?php

use App\Models\App;
use App\Models\Device;
use Livewire\Volt\Component;
use Illuminate\Support\Carbon;

new class extends Component
{
    public $device;

    public $schedule;

    public function mount($id)
    {
        $this->device = Device::find($id);
        if (! $this->device) {
            abort(404);
        }

        $this->schedule = $this->device->schedule;
    }

    public function add()
    {
        $this->schedule[] = [];
    }

    public function remove($index)
    {
        unset($this->schedule[$index]);
    }

    public function save()
    {
        $this->device->schedule = $this->schedule;
        $this->device->save();
    }

    private function timeIntervals()
    {
        $start = Carbon::createFromTime(0, 0);
        $end = Carbon::createFromTime(23, 59);
        $times = [];

        while ($start <= $end) {
            $times[] = $start->format('H:i');
            $start->addMinutes(15);
        }

        return $times;
    }

    public function refreshKey()
    {
        $this->device->refreshKey();
    }
}; ?>

<div>
    <div class="flex gap-4">
        <flux:input icon="key" :value="$device->infoUrl()" readonly copyable />
        <flux:button wire:click="refreshKey">
            Refresh
        </flux:button>
    </div>
    <h2>
        Schedule
    </h2>
    <div>
        @foreach ($schedule as $index => $item)
            <div class="flex items-end gap-4">
                <flux:select wire:model.fill="schedule.{{ $index }}.from" label="From">
                    @foreach($this->timeIntervals() as $time)
                        <flux:select.option>{{ $time }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.fill="schedule.{{ $index }}.to" label="To">
                    @foreach($this->timeIntervals() as $time)
                        <flux:select.option>{{ $time }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.fill="schedule.{{ $index }}.app" label="App">
                    @foreach(App::all() as $app)
                        <flux:select.option value="{{ $app->id }}">{{ $app->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.fill="schedule.{{ $index }}.frequency" label="Frequency">
                    @foreach($this->timeIntervals() as $time)
                        <flux:select.option>{{ $time }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:button wire:click="remove({{ $index }})">
                    Remove
                </flux:button>
            </div>
        @endforeach
    </div>
    <flux:button wire:click="add">
        Add
    </flux:button>
    <flux:button wire:click="save">
        Save
    </flux:button>
</div>
