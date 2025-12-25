<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DashboardSearch extends Widget
{
    protected static string $view = 'filament.widgets.dashboard-search';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public ?string $search = '';

    public function updatedSearch(): void
    {
        // This will trigger the view to update when search changes
    }
}

