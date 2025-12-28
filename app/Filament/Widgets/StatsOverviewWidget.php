<?php

namespace App\Filament\Widgets;

use App\Models\Sender;
use App\Models\Package;
use App\Models\TravelerTicket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalSenders = Sender::where('type', 'sender')->count();
        $totalTravelers = Sender::where('type', 'traveler')->count();
        $activeSenders = Sender::where('type', 'sender')->where('status', 'active')->where('is_verified', true)->count();
        $activeTravelers = Sender::where('type', 'traveler')->where('status', 'active')->where('is_verified', true)->count();
        $totalPackages = Package::count();
        $activePackages = Package::whereIn('status', ['approved', 'paid', 'in_transit'])->count();
        $totalTickets = TravelerTicket::whereHas('traveler', function ($query) {
            $query->where('type', 'traveler');
        })->count();
        $activeTickets = TravelerTicket::whereHas('traveler', function ($query) {
            $query->where('type', 'traveler');
        })->where('status', 'active')->count();

        return [
            Stat::make('Total Senders', $totalSenders)
                ->description('Active: ' . $activeSenders)
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
            
            Stat::make('Total Travelers', $totalTravelers)
                ->description('Active: ' . $activeTravelers)
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart([3, 2, 4, 5, 3, 4, 3]),
            
            Stat::make('Total Packages', $totalPackages)
                ->description('Active: ' . $activePackages)
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning')
                ->chart([5, 4, 6, 7, 5, 6, 4]),
            
            Stat::make('Active Tickets', $activeTickets)
                ->description('Total: ' . $totalTickets)
                ->descriptionIcon('heroicon-m-ticket')
                ->color('primary')
                ->chart([2, 3, 4, 3, 5, 4, 3]),
        ];
    }
}

