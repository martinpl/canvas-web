<?php

use Livewire\Volt\Component;

new class extends Component {}; ?>

<div>
    @foreach(App\Models\Log::with('device')->get() as $log)
        <div>
            {{ $log->type }}
            {{ $log->message }}
            @if ($log->device)
                ({{ $log->device->name }})
            @endif
            <span title="{{ $log->created_at }}">
                {{ $log->created_at->diffForHumans() }}
            </span>
        </div>
    @endforeach
</div>
