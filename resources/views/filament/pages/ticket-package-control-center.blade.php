<x-filament-panels::page>
    <x-filament::tabs>
        <x-filament::tabs.item
            :href="request()->fullUrlWithQuery(['tab' => 'tickets'])"
            wire:navigate
            :active="request('tab', 'tickets') === 'tickets'"
        >
            Tickets
        </x-filament::tabs.item>

        <x-filament::tabs.item
            :href="request()->fullUrlWithQuery(['tab' => 'packages'])"
            wire:navigate
            :active="request('tab', 'tickets') === 'packages'"
        >
            Packages
        </x-filament::tabs.item>
    </x-filament::tabs>

    <div class="mt-4" wire:key="tab-{{ request('tab', 'tickets') }}">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
