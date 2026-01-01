<?php

namespace App\Filament\Widgets;

use App\Models\TravelerTicket;
use Filament\Widgets\ChartWidget;

class TicketsChartWidget extends ChartWidget
{
    protected static ?string $heading = '🎫 Traveler Tickets Status Distribution';

    protected static ?string $description = 'Complete breakdown of all traveler tickets by status';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'sm' => 'full',
        'md' => 2,
        'lg' => 2,
        'xl' => 2,
    ];

    protected function getData(): array
    {
        $statuses = TravelerTicket::whereHas('traveler', function ($query) {
            $query->where('type', 'traveler');
        })
        ->selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();

        $labels = [
            'draft' => 'Draft',
            'active' => 'Active',
            'matched' => 'Matched',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        $data = [];
        $colors = [];
        $labelNames = [];

        foreach ($labels as $key => $label) {
            $data[] = $statuses[$key] ?? 0;
            $labelNames[] = $label;
            
            $colors[] = match($key) {
                'draft' => 'rgba(156, 163, 175, 0.9)',      // gray
                'active' => 'rgba(59, 130, 246, 0.9)',       // blue
                'matched' => 'rgba(34, 197, 94, 0.9)',       // green
                'completed' => 'rgba(16, 185, 129, 0.9)',   // emerald
                'cancelled' => 'rgba(239, 68, 68, 0.9)',   // red
                default => 'rgba(156, 163, 175, 0.9)',
            };
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tickets',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => array_map(function($color) {
                        return str_replace('0.9', '1', $color);
                    }, $colors),
                    'borderWidth' => 2,
                    'borderRadius' => 8,
                    'barThickness' => 'flex',
                    'maxBarThickness' => 60,
                ],
            ],
            'labels' => $labelNames,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
                        'stepSize' => 1,
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
        ];
    }
}

