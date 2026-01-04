<x-filament-panels::page>
    <x-filament::tabs>
        <x-filament::tabs.item 
            wire:click="switchTab('tickets')"
            :active="$activeTab === 'tickets'"
        >
            Tickets
        </x-filament::tabs.item>
        <x-filament::tabs.item 
            wire:click="switchTab('packages')"
            :active="$activeTab === 'packages'"
        >
            Packages
        </x-filament::tabs.item>
    </x-filament::tabs>

    <div class="mt-4">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
