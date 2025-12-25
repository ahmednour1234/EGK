<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Switch User Type View</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Switch between viewing Senders and Travelers in the dashboard. You will be logged out and need to login again.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border-2 border-blue-200 dark:border-blue-800 rounded-lg p-6 hover:border-blue-400 dark:hover:border-blue-600 transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400">Sender View</h3>
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        View and manage users who send packages.
                    </p>
                    <x-filament::button 
                        wire:click="switchToSender"
                        wire:confirm="You will be logged out and need to login again. Continue?"
                        color="primary" 
                        class="w-full">
                        Switch to Sender View
                    </x-filament::button>
                </div>

                <div class="border-2 border-orange-200 dark:border-orange-800 rounded-lg p-6 hover:border-orange-400 dark:hover:border-orange-600 transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-orange-600 dark:text-orange-400">Traveler View</h3>
                        <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        View and manage users who travel and deliver packages.
                    </p>
                    <x-filament::button 
                        wire:click="switchToTraveler"
                        wire:confirm="You will be logged out and need to login again. Continue?"
                        color="warning" 
                        class="w-full">
                        Switch to Traveler View
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
