<x-filament-panels::page>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex gap-6">
            <a
                href="{{ request()->fullUrlWithQuery(['tab' => 'tickets']) }}"
                class="py-3 text-sm font-medium border-b-2
                    {{ request('tab', 'tickets') === 'tickets'
                        ? 'border-primary-600 text-primary-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Tickets
            </a>

            <a
                href="{{ request()->fullUrlWithQuery(['tab' => 'packages']) }}"
                class="py-3 text-sm font-medium border-b-2
                    {{ request('tab', 'tickets') === 'packages'
                        ? 'border-primary-600 text-primary-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Packages
            </a>
        </nav>
    </div>

    <div class="mt-4" wire:key="tab-{{ request('tab', 'tickets') }}">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
