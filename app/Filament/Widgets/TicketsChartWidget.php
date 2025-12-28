<?php

namespace App\Filament\Widgets;

use App\Models\TravelerTicket;
use Filament\Widgets\ChartWidget;

class TicketsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Traveler Tickets Status';

    protected static ?string $description = 'Distribution of traveler tickets by status';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

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
                'draft' => 'rgba(156, 163, 175, 0.8)',    // gray
                'active' => 'rgba(59, 130, 246, 0.8)',     // blue
                'matched' => 'rgba(34, 197, 94, 0.8)',     // green
                'completed' => 'rgba(16, 185, 129, 0.8)',  // teal
                'cancelled' => 'rgba(239, 68, 68, 0.8)',   // red
                default => 'rgba(156, 163, 175, 0.8)',
            };
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tickets',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labelNames,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

