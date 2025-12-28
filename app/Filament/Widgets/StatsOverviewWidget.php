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
        // Senders Statistics
        $totalSenders = Sender::where('type', 'sender')->count();
        $activeSenders = Sender::where('type', 'sender')
            ->where('status', 'active')
            ->where('is_verified', true)
            ->count();
        $verifiedSenders = Sender::where('type', 'sender')
            ->where('is_verified', true)
            ->count();
        $inactiveSenders = $totalSenders - $activeSenders;
        $senderPercentage = $totalSenders > 0 ? round(($activeSenders / $totalSenders) * 100, 1) : 0;
        
        // Get last 7 days data for chart
        $senderChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $senderChartData[] = Sender::where('type', 'sender')
                ->whereDate('created_at', '<=', $date)
                ->count();
        }

        // Travelers Statistics
        $totalTravelers = Sender::where('type', 'traveler')->count();
        $activeTravelers = Sender::where('type', 'traveler')
            ->where('status', 'active')
            ->where('is_verified', true)
            ->count();
        $verifiedTravelers = Sender::where('type', 'traveler')
            ->where('is_verified', true)
            ->count();
        $travelerPercentage = $totalTravelers > 0 ? round(($activeTravelers / $totalTravelers) * 100, 1) : 0;
        
        // Get last 7 days data for chart
        $travelerChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $travelerChartData[] = Sender::where('type', 'traveler')
                ->whereDate('created_at', '<=', $date)
                ->count();
        }

        // Packages Statistics
        $totalPackages = Package::count();
        $activePackages = Package::whereIn('status', ['approved', 'paid', 'in_transit'])->count();
        $deliveredPackages = Package::where('status', 'delivered')->count();
        $pendingPackages = Package::where('status', 'pending_review')->count();
        $packagePercentage = $totalPackages > 0 ? round(($activePackages / $totalPackages) * 100, 1) : 0;
        
        // Get last 7 days data for chart
        $packageChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $packageChartData[] = Package::whereDate('created_at', '<=', $date)->count();
        }

        // Tickets Statistics
        $totalTickets = TravelerTicket::whereHas('traveler', function ($query) {
            $query->where('type', 'traveler');
        })->count();
        $activeTickets = TravelerTicket::whereHas('traveler', function ($query) {
            $query->where('type', 'traveler');
        })->where('status', 'active')->count();
        $completedTickets = TravelerTicket::whereHas('traveler', function ($query) {
            $query->where('type', 'traveler');
        })->where('status', 'completed')->count();
        $ticketPercentage = $totalTickets > 0 ? round(($activeTickets / $totalTickets) * 100, 1) : 0;
        
        // Get last 7 days data for chart
        $ticketChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $ticketChartData[] = TravelerTicket::whereHas('traveler', function ($query) {
                $query->where('type', 'traveler');
            })->whereDate('created_at', '<=', $date)->count();
        }

        return [
            Stat::make('Total Senders', number_format($totalSenders))
                ->description("{$activeSenders} active • {$verifiedSenders} verified • {$senderPercentage}% active rate")
                ->descriptionIcon('heroicon-o-user-circle')
                ->icon('heroicon-o-users')
                ->color('success')
                ->chart($senderChartData)
                ->chartColor('success'),
            
            Stat::make('Total Travelers', number_format($totalTravelers))
                ->description("{$activeTravelers} active • {$verifiedTravelers} verified • {$travelerPercentage}% active rate")
                ->descriptionIcon('heroicon-o-globe-alt')
                ->icon('heroicon-o-user-group')
                ->color('info')
                ->chart($travelerChartData)
                ->chartColor('info'),
            
            Stat::make('Total Packages', number_format($totalPackages))
                ->description("{$activePackages} active • {$deliveredPackages} delivered • {$pendingPackages} pending")
                ->descriptionIcon('heroicon-o-cube')
                ->icon('heroicon-o-archive-box')
                ->color('warning')
                ->chart($packageChartData)
                ->chartColor('warning'),
            
            Stat::make('Active Tickets', number_format($activeTickets))
                ->description("{$totalTickets} total • {$completedTickets} completed • {$ticketPercentage}% active")
                ->descriptionIcon('heroicon-o-ticket')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('primary')
                ->chart($ticketChartData)
                ->chartColor('primary'),
        ];
    }
}

