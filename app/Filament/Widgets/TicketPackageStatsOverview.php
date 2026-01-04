<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use App\Models\TravelerTicket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class TicketPackageStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Package Statistics
        $totalPackages = Package::count();
        
        $linkedPackages = Package::whereNotNull('ticket_id')->count();
        
        $deliveredPackages = Package::where('status', 'delivered')->count();
        
        // Delayed Packages: now > delivery_datetime AND status != delivered
        $delayedPackages = Package::where('status', '!=', 'delivered')
            ->whereNotNull('delivery_date')
            ->whereNotNull('delivery_time')
            ->where(DB::raw("CONCAT(delivery_date, ' ', delivery_time)"), '<', now()->toDateTimeString())
            ->count();
        
        // Delivered Late: status=delivered AND delivered_at > delivery_datetime
        $deliveredLate = Package::where('status', 'delivered')
            ->whereNotNull('delivered_at')
            ->where(function ($query) {
                $query->whereNotNull('delivery_date')
                    ->whereNotNull('delivery_time')
                    ->whereColumn('delivered_at', '>', DB::raw("CONCAT(delivery_date, ' ', delivery_time)"));
            })
            ->count();
        
        // New Packages (last 24 hours)
        $newPackages = Package::where('created_at', '>=', now()->subDay())->count();
        
        // Ticket Statistics
        $totalTickets = TravelerTicket::count();
        
        // Unlinked Tickets (tickets with no packages)
        $unlinkedTickets = TravelerTicket::doesntHave('packages')->count();
        
        // Open Tickets (status = active)
        $openTickets = TravelerTicket::where('status', 'active')->count();

        return [
            Stat::make('Total Packages', number_format($totalPackages))
                ->description("{$linkedPackages} linked • {$newPackages} new (24h)")
                ->descriptionIcon('heroicon-o-archive-box', 'before')
                ->icon('heroicon-o-archive-box')
                ->color('primary'),
            
            Stat::make('Delivered Packages', number_format($deliveredPackages))
                ->description("{$deliveredLate} delivered late")
                ->descriptionIcon('heroicon-o-check-circle', 'before')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('Delayed Packages', number_format($delayedPackages))
                ->description("Needs attention")
                ->descriptionIcon('heroicon-o-exclamation-triangle', 'before')
                ->icon('heroicon-o-clock')
                ->color('danger'),
            
            Stat::make('Total Tickets', number_format($totalTickets))
                ->description("{$openTickets} open • {$unlinkedTickets} unlinked")
                ->descriptionIcon('heroicon-o-ticket', 'before')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('info'),
        ];
    }
}
