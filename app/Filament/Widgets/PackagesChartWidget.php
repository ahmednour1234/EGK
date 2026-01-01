<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use Filament\Widgets\ChartWidget;

class PackagesChartWidget extends ChartWidget
{
    protected static ?string $heading = '📦 Packages Status Distribution';

    protected static ?string $description = 'Visual breakdown of all packages by current status';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'sm' => 1,
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $statuses = [
            'pending_review' => Package::where('status', 'pending_review')->count(),
            'approved' => Package::where('status', 'approved')->count(),
            'paid' => Package::where('status', 'paid')->count(),
            'in_transit' => Package::where('status', 'in_transit')->count(),
            'delivered' => Package::where('status', 'delivered')->count(),
            'cancelled' => Package::where('status', 'cancelled')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Packages',
                    'data' => array_values($statuses),
                    'backgroundColor' => [
                        'rgba(251, 191, 36, 0.9)',      // pending_review - amber
                        'rgba(59, 130, 246, 0.9)',      // approved - blue
                        'rgba(34, 197, 94, 0.9)',       // paid - green
                        'rgba(168, 85, 247, 0.9)',     // in_transit - purple
                        'rgba(16, 185, 129, 0.9)',      // delivered - emerald
                        'rgba(156, 163, 175, 0.9)',    // cancelled - gray
                    ],
                    'borderColor' => [
                        'rgba(251, 191, 36, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(168, 85, 247, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(156, 163, 175, 1)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Pending Review', 'Approved', 'Paid', 'In Transit', 'Delivered', 'Cancelled'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
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
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => true,
            'cutout' => '60%',
        ];
    }
}

