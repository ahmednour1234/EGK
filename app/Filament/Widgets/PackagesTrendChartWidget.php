<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use Filament\Widgets\ChartWidget;

class PackagesTrendChartWidget extends ChartWidget
{
    protected static ?string $heading = '📈 Packages Trend (7 Days)';

    protected static ?string $description = 'Daily package creation and delivery trends';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'sm' => 1,
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
    ];

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
                    'backgroundColor' => 'rgba(59, 130, 246, 0.15)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.5,
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                    'pointBackgroundColor' => 'rgba(59, 130, 246, 1)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                ],
                [
                    'label' => 'Delivered',
                    'data' => $deliveredData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.15)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.5,
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                    'pointBackgroundColor' => 'rgba(34, 197, 94, 1)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
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
                    'grid' => [
                        'color' => 'rgba(255, 255, 255, 0.05)',
                    ],
                    'ticks' => [
                        'color' => 'rgba(255, 255, 255, 0.7)',
                        'font' => ['size' => 11],
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'color' => 'rgba(255, 255, 255, 0.7)',
                        'font' => ['size' => 11],
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'padding' => 15,
                        'usePointStyle' => true,
                        'font' => [
                            'size' => 12,
                            'weight' => '500',
                        ],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'padding' => 12,
                    'titleFont' => ['size' => 14, 'weight' => 'bold'],
                    'bodyFont' => ['size' => 13],
                    'displayColors' => true,
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => true,
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }
}

