<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $routePath = 'dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardSearch::class,
            \App\Filament\Widgets\NewTravelerTicketsWidget::class,
            \Filament\Widgets\AccountWidget::class,
            \Filament\Widgets\FilamentInfoWidget::class,
        ];
    }
}

