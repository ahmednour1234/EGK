<?php

namespace App\Filament\Pages;

use App\Models\Package;
use App\Models\TravelerTicket;
use App\Models\User;
use App\Services\TicketPackageMatcher;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TicketPackageControlCenter extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-command-line';

    protected static string $view = 'filament.pages.ticket-package-control-center';

    protected static ?string $navigationLabel = 'Control Center';

    protected static ?string $title = 'Ticket & Package Control Center';

    protected static ?string $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 1;

    public ?string $activeTab = 'tickets';

    public ?string $searchQuery = null;
    public ?string $packageStatusFilter = null;
    public ?string $ticketStatusFilter = null;
    public ?string $delayedFilter = null;
    public ?string $pickupCityFilter = null;
    public ?string $deliveryCityFilter = null;
    public ?string $tripTypeFilter = null;
    public ?string $assigneeFilter = null;
    public ?string $pickupDateFrom = null;
    public ?string $pickupDateTo = null;
    public ?string $deliveryDateFrom = null;
    public ?string $deliveryDateTo = null;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user && (
            $user->hasPermission('manage-packages') || 
            $user->hasPermission('manage-traveler-tickets')
        );
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\TicketPackageStatsOverview::class,
        ];
    }

    protected function getTicketsTableQuery(): Builder
    {
        $query = TravelerTicket::query()->with(['packages', 'assignee']);

        if ($this->ticketStatusFilter) {
            $query->where('status', $this->ticketStatusFilter);
        }

        if ($this->tripTypeFilter) {
            $query->where('trip_type', $this->tripTypeFilter);
        }

        if ($this->assigneeFilter) {
            $query->where('assignee_id', $this->assigneeFilter);
        }

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->searchQuery}%")
                    ->orWhere('from_city', 'like', "%{$this->searchQuery}%")
                    ->orWhere('to_city', 'like', "%{$this->searchQuery}%");
            });
        }

        return $query;
    }

    protected function getPackagesTableQuery(): Builder
    {
        $query = Package::query()->with(['ticket']);

        if ($this->packageStatusFilter) {
            $query->where('status', $this->packageStatusFilter);
        }

        if ($this->pickupCityFilter) {
            $query->where('pickup_city', $this->pickupCityFilter);
        }

        if ($this->deliveryCityFilter) {
            $query->where('delivery_city', $this->deliveryCityFilter);
        }

        if ($this->delayedFilter === 'yes') {
            $query->where('status', '!=', 'delivered')
                ->where(function ($q) {
                    $q->whereNotNull('delivery_date')
                        ->whereNotNull('delivery_time')
                        ->where(DB::raw("CONCAT(delivery_date, ' ', delivery_time)"), '<', now());
                });
        }

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('tracking_number', 'like', "%{$this->searchQuery}%")
                    ->orWhere('receiver_mobile', 'like', "%{$this->searchQuery}%");
            });
        }

        return $query;
    }

    protected function getMatchResults($ticket): string
    {
        $matcher = new TicketPackageMatcher();
        $packages = Package::whereIn('status', ['approved', 'paid'])
            ->where('compliance_confirmed', true)
            ->whereNull('ticket_id')
            ->get();

        $matches = [];
        foreach ($packages as $package) {
            $score = $matcher->match($package, $ticket);
            if ($score > 0) {
                $matches[] = [
                    'package' => $package,
                    'score' => $score,
                ];
            }
        }

        usort($matches, fn ($a, $b) => $b['score'] <=> $a['score']);
        $topMatches = array_slice($matches, 0, 10);

        if (empty($topMatches)) {
            return 'No matching packages found.';
        }

        $html = '<div class="space-y-2">';
        foreach ($topMatches as $match) {
            $package = $match['package'];
            $score = $match['score'];
            $html .= "<div class='p-2 border rounded'>
                <strong>{$package->tracking_number}</strong> - Score: {$score}%
                <br><small>{$package->pickup_city} → {$package->delivery_city}</small>
            </div>";
        }
        $html .= '</div>';

        return $html;
    }

    protected function getTableQuery(): Builder
    {
        return $this->activeTab === 'tickets' 
            ? $this->getTicketsTableQuery() 
            : $this->getPackagesTableQuery();
    }

    protected function table(Table $table): Table
    {
        return $this->activeTab === 'tickets' 
            ? $this->configureTicketsTable($table) 
            : $this->configurePackagesTable($table);
    }

    protected function configureTicketsTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('trip_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'one-way' ? 'One-way' : 'Round trip')
                    ->color(fn ($state) => $state === 'one-way' ? 'info' : 'warning')
                    ->sortable(),
                TextColumn::make('transport_type')
                    ->label('Transport')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'matched' => 'Matched',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($state),
                    })
                    ->color(fn ($state) => match($state) {
                        'draft' => 'gray',
                        'active' => 'info',
                        'matched', 'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('from_city')
                    ->label('From')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('to_city')
                    ->label('To')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('packages_count')
                    ->label('Linked Packages')
                    ->badge()
                    ->counts('packages')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray')
                    ->sortable(),
                TextColumn::make('assignee.name')
                    ->label('Assigned To')
                    ->searchable()
                    ->sortable()
                    ->default('—'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'matched' => 'Matched',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('trip_type')
                    ->options([
                        'one-way' => 'One-way',
                        'round-trip' => 'Round trip',
                    ]),
                SelectFilter::make('assignee_id')
                    ->label('Assigned To')
                    ->relationship('assignee', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('findMatches')
                    ->label('Find Matches')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('primary')
                    ->slideOver()
                    ->form([
                        Forms\Components\Placeholder::make('matches')
                            ->label('Top Matching Packages')
                            ->content(fn ($record) => $this->getMatchResults($record)),
                    ])
                    ->visible(fn ($record) => $record->status === 'active' && auth()->user()?->hasPermission('link-ticket-package')),
                Tables\Actions\Action::make('linkPackage')
                    ->label('Link Package')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->form([
                        Select::make('package_id')
                            ->label('Package')
                            ->options(fn () => Package::whereNull('ticket_id')->pluck('tracking_number', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        Package::find($data['package_id'])->update(['ticket_id' => $record->id]);
                        Notification::make()
                            ->title('Package linked successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn () => auth()->user()?->hasPermission('link-ticket-package')),
                Tables\Actions\Action::make('unlinkPackage')
                    ->label('Unlink Package')
                    ->icon('heroicon-o-link-slash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Select::make('package_id')
                            ->label('Package to Unlink')
                            ->options(fn ($record) => $record->packages->pluck('tracking_number', 'id'))
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        Package::find($data['package_id'])->update(['ticket_id' => null]);
                        Notification::make()
                            ->title('Package unlinked successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->packages->count() > 0 && auth()->user()?->hasPermission('link-ticket-package')),
                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Active',
                                'matched' => 'Matched',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update($data);
                        Notification::make()
                            ->title('Status updated successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn () => auth()->user()?->hasPermission('manage-traveler-tickets')),
                Tables\Actions\Action::make('assign')
                    ->label('Assign')
                    ->icon('heroicon-o-user')
                    ->form([
                        Select::make('assignee_id')
                            ->label('Assign To')
                            ->relationship('assignee', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update($data);
                        Notification::make()
                            ->title('Ticket assigned successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn () => auth()->user()?->hasPermission('assign-tickets')),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function configurePackagesTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending_review' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'paid' => 'Paid',
                        'in_transit' => 'In Transit',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->color(fn ($state) => match($state) {
                        'pending_review' => 'warning',
                        'approved' => 'info',
                        'rejected' => 'danger',
                        'paid', 'delivered' => 'success',
                        'in_transit' => 'primary',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('pickup_city')
                    ->label('Pickup')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('delivery_city')
                    ->label('Delivery')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pickup_datetime')
                    ->label('Pickup Date')
                    ->dateTime()
                    ->getStateUsing(fn ($record) => $record->pickup_datetime)
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('pickup_date', $direction)->orderBy('pickup_time', $direction);
                    }),
                TextColumn::make('delivery_datetime')
                    ->label('Delivery Date')
                    ->dateTime()
                    ->getStateUsing(fn ($record) => $record->delivery_datetime)
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('delivery_date', $direction)->orderBy('delivery_time', $direction);
                    }),
                TextColumn::make('receiver_mobile')
                    ->label('Receiver Mobile')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('compliance_confirmed')
                    ->label('Compliance')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('is_delayed')
                    ->label('Delayed')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->is_delayed ? 'Yes' : 'No')
                    ->color(fn ($state) => $state === 'Yes' ? 'danger' : 'success'),
                TextColumn::make('delivered_at')
                    ->label('Delivered At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ticket.id')
                    ->label('Linked Ticket')
                    ->formatStateUsing(fn ($state) => $state ? 'Ticket #' . $state : '—')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending_review' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'paid' => 'Paid',
                        'in_transit' => 'In Transit',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('ticket_id')
                    ->label('Linked Ticket')
                    ->relationship('ticket', 'id')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        Select::make('status')
                            ->options([
                                'pending_review' => 'Pending Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'paid' => 'Paid',
                                'in_transit' => 'In Transit',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update($data);
                        Notification::make()
                            ->title('Status updated successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn () => auth()->user()?->hasPermission('manage-packages')),
                Tables\Actions\Action::make('markDelivered')
                    ->label('Mark Delivered')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        DatePicker::make('delivered_at')
                            ->label('Delivered At')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'delivered',
                            'delivered_at' => $data['delivered_at'],
                        ]);
                        Notification::make()
                            ->title('Package marked as delivered')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status !== 'delivered' && auth()->user()?->hasPermission('manage-packages')),
                Tables\Actions\Action::make('viewLinkedTickets')
                    ->label('View Linked Tickets')
                    ->icon('heroicon-o-eye')
                    ->slideOver()
                    ->form([
                        Forms\Components\Placeholder::make('tickets')
                            ->label('Linked Tickets')
                            ->content(fn ($record) => $record->ticket ? 'Ticket #' . $record->ticket->id : 'No linked tickets'),
                    ])
                    ->visible(fn ($record) => $record->ticket_id !== null),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }
}
