@props([
    'title',
    'menus' => [
        [
            'key' => 'dashboard',
            'title' => __('Dashboard'),
            'icon' => 'layout-grid',
        ],
        [
            'key' => 'apps',
            'title' => __('Apps'),
            'icon' => 'layout-grid',
        ],
        [
            'key' => 'logs',
            'title' => __('Logs'),
            'icon' => 'layout-grid',
        ],
    ],
    'sideMenus' => [
        [
            'href' => 'https://github.com/martinpl/canvas-web',
            'title' => __('Repository'),
            'icon' => 'folder-git-2'
        ]
    ]
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <a href="{{ route('dashboard') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navbar class="-mb-px max-lg:hidden">
                @foreach($menus as $menu)
                    <flux:navbar.item :icon="$menu['icon']" :href="route($menu['key'])" :current="request()->routeIs($menu['key'])" wire:navigate>
                        {{ $menu['title'] }}
                    </flux:navbar.item>
                @endforeach
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="mr-1.5 space-x-0.5 py-0!">
                @foreach($sideMenus as $sideMenu)
                    <flux:tooltip :content="__('Repository')" position="bottom">
                        <flux:navbar.item
                            class="h-10 max-lg:hidden [&>div>svg]:size-5"
                            :icon="$sideMenu['icon']"
                            :href="$sideMenu['href']"
                            :label="$sideMenu['title']"
                            target="_blank"
                        />
                    </flux:tooltip>
                @endforeach
            </flux:navbar>

            <!-- Desktop User Menu -->
            <flux:dropdown position="top" align="end">
                <flux:profile
                    class="cursor-pointer"
                    :initials="auth()->user()->initials()"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="ml-1 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')">
                    @foreach($menus as $menu)
                        <flux:navlist.item :icon="$menu['icon']" :href="route($menu['key'])" :current="request()->routeIs($menu['key'])" wire:navigate>
                            {{ $menu['title'] }}
                        </flux:navlist.item>
                    @endforeach
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                @foreach($sideMenus as $sideMenu)
                    <flux:navlist.item :icon="$sideMenu['icon']" :href="$sideMenu['href']" target="_blank">
                        {{ $sideMenu['title'] }}
                    </flux:navlist.item>
                @endforeach
            </flux:navlist>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
