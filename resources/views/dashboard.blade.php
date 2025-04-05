@use(App\Models\Device)

<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            @foreach (Device::all() as $device)
                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-8">
                    <flux:heading size="xl">
                        <a href="{{ route('device', $device->id) }}">
                            {{ $device->name }}
                        </a>
                    </flux:heading>
                    {{ $device->battery }}<br>
                </div>
            @endforeach
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-8">
                <livewire:add-device />
            </div>
        </div>
    </div>
</x-layouts.app>
