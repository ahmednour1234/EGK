<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-filament::icon icon="heroicon-o-magnifying-glass" class="h-5 w-5 text-gray-400" />
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search resources, pages, and settings..."
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                />
            </div>
        </div>
        
        @if(!empty($this->search))
            <div class="mt-4 space-y-2">
                @php
                    $results = [];
                    $searchTerm = strtolower($this->search);
                    
                    // Search in resources
                    $resources = [
                        ['name' => 'Users', 'url' => route('filament.admin.resources.users.index'), 'icon' => 'heroicon-o-users', 'type' => 'Resource'],
                        ['name' => 'Roles', 'url' => route('filament.admin.resources.roles.index'), 'icon' => 'heroicon-o-shield-check', 'type' => 'Resource'],
                        ['name' => 'Permissions', 'url' => route('filament.admin.resources.permissions.index'), 'icon' => 'heroicon-o-key', 'type' => 'Resource'],
                        ['name' => 'Pages', 'url' => route('filament.admin.resources.pages.index'), 'icon' => 'heroicon-o-document-text', 'type' => 'Resource'],
                        ['name' => 'FAQs', 'url' => route('filament.admin.resources.faqs.index'), 'icon' => 'heroicon-o-question-mark-circle', 'type' => 'Resource'],
                        ['name' => 'Settings', 'url' => route('filament.admin.resources.settings.index'), 'icon' => 'heroicon-o-key', 'type' => 'Resource'],
                        ['name' => 'Cities', 'url' => route('filament.admin.resources.cities.index'), 'icon' => 'heroicon-o-map-pin', 'type' => 'Resource'],
                        ['name' => 'Packages', 'url' => route('filament.admin.resources.packages.index'), 'icon' => 'heroicon-o-cube', 'type' => 'Resource'],
                        ['name' => 'Package Types', 'url' => route('filament.admin.resources.package-types.index'), 'icon' => 'heroicon-o-tag', 'type' => 'Resource'],
                    ];
                    
                    foreach ($resources as $resource) {
                        if (stripos($resource['name'], $searchTerm) !== false) {
                            $results[] = $resource;
                        }
                    }
                    
                    // Search in pages
                    $pages = [
                        ['name' => 'App Settings', 'url' => route('filament.admin.pages.app-settings'), 'icon' => 'heroicon-o-cog-6-tooth', 'type' => 'Page'],
                        ['name' => 'My Profile', 'url' => route('filament.admin.pages.profile'), 'icon' => 'heroicon-o-user-circle', 'type' => 'Page'],
                    ];
                    
                    foreach ($pages as $page) {
                        if (stripos($page['name'], $searchTerm) !== false) {
                            $results[] = $page;
                        }
                    }
                @endphp
                
                @if(count($results) > 0)
                    <div class="space-y-1 max-h-64 overflow-y-auto">
                        @foreach($results as $result)
                            <a href="{{ $result['url'] }}" class="flex items-center gap-3 p-3 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                <x-filament::icon :icon="$result['icon']" class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $result['name'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $result['type'] }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No results found for "{{ $this->search }}"</p>
                @endif
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>

