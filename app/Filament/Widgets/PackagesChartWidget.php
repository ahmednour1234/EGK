<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use Filament\Widgets\ChartWidget;

class PackagesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Packages Status Distribution';

    protected static ?string $description = 'Breakdown of all packages by current status';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

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
                        'rgba(251, 191, 36, 0.8)',  // pending_review - yellow
                        'rgba(59, 130, 246, 0.8)',   // approved - blue
                        'rgba(34, 197, 94, 0.8)',    // paid - green
                        'rgba(168, 85, 247, 0.8)',   // in_transit - purple
                        'rgba(16, 185, 129, 0.8)',  // delivered - teal
                        'rgba(156, 163, 175, 0.8)', // cancelled - gray
                    ],
                ],
            ],
            'labels' => ['Pending Review', 'Approved', 'Paid', 'In Transit', 'Delivered', 'Cancelled'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

