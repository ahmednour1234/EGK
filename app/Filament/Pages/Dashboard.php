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
            \App\Filament\Widgets\StatsOverviewWidget::class,
            \App\Filament\Widgets\PackagesChartWidget::class,
            \App\Filament\Widgets\TicketsChartWidget::class,
            \App\Filament\Widgets\NewTravelerTicketsWidget::class,
            \Filament\Widgets\AccountWidget::class,
        ];
    }
}

