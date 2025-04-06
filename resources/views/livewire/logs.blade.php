<?php

use App\Models\Log;
use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

new 
#[Title('Logs')]
class extends Component {
    #[Computed]
    public function list() 
    {
        return Log::with('device')->get();
    }

    public function remove($id) 
    {
        Log::where('id', $id)->delete();
    }

    public function removeAll() 
    {
        Log::truncate();
    }
}; ?>

<div>
    @if ($this->list->isNotEmpty())
        <flux:button wire:click="removeAll">
            Remove all
        </flux:button>
    @endif
    @forelse($this->list as $log)
        <div>
            {{ $log->type }}
            {{ $log->message }}
            @if ($log->device)
                ({{ $log->device->name }})
            @endif
            <span title="{{ $log->created_at }}">
                {{ $log->created_at->diffForHumans() }}
            </span>
            <flux:button wire:click="remove({{ $log->id }})">
                Remove
            </flux:button>
        </div>
    @empty
        Empty
    @endforelse
</div>
