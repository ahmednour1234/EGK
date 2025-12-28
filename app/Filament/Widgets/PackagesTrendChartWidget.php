<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use Filament\Widgets\ChartWidget;

class PackagesTrendChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Packages Trend (7 Days)';

    protected static ?string $description = 'Daily package creation trend';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $labels = [];
        $createdData = [];
        $deliveredData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');
            
            $createdData[] = Package::whereDate('created_at', $date->format('Y-m-d'))->count();
            $deliveredData[] = Package::where('status', 'delivered')
                ->whereDate('updated_at', $date->format('Y-m-d'))
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Created',
                    'data' => $createdData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Delivered',
                    'data' => $deliveredData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
        ];
    }
}

