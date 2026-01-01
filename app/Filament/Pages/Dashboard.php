<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $routePath = 'dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardSearch::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverviewWidget::class,
            \App\Filament\Widgets\PackagesChartWidget::class,
            \App\Filament\Widgets\PackagesTrendChartWidget::class,
            \App\Filament\Widgets\TicketsChartWidget::class,
            \App\Filament\Widgets\NewTravelerTicketsWidget::class,
            \Filament\Widgets\AccountWidget::class,
        ];
    }

    protected function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 2,
            'lg' => 3,
            'xl' => 4,
        ];
    }
}

