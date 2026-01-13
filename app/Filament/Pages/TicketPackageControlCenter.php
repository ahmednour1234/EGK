<?php

namespace App\Filament\Pages;

use App\Models\Package;
use App\Models\TravelerTicket;
use App\Services\TicketPackageMatcher;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class TicketPackageControlCenter extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-command-line';
    protected static string $view = 'filament.pages.ticket-package-control-center';
    protected static ?string $navigationLabel = 'Control Center';
    protected static ?string $title = 'Ticket & Package Control Center';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 1;

    /** Tabs */
    public string $activeTab = 'tickets';

    /** Local filters */
    public ?string $searchQuery = null;
    public ?string $packageStatusFilter = null;
    public ?string $ticketStatusFilter = null;
    public ?string $delayedFilter = null;
    public ?string $pickupCityFilter = null;
    public ?string $deliveryCityFilter = null;
    public ?string $tripTypeFilter = null;
    public ?string $assigneeFilter = null;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        return $user && (
            $user->hasPermission('manage-packages')
            || $user->hasPermission('manage-traveler-tickets')
        );
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\TicketPackageStatsOverview::class,
        ];
    }

    public function switchTab(string $tab): void
    {
        if (! in_array($tab, ['tickets', 'packages'], true)) {
            return;
        }

        $this->activeTab = $tab;

        // ✅ يمنع لخبطة pagination + يخفف rerenders
        $this->resetTable();
    }

    /** =========================
     *  Queries (optimized)
     *  ========================= */

    protected function getTicketsTableQuery(): Builder
    {
        $q = TravelerTicket::query()
            ->select([
                'id',
                'trip_type',
                'transport_type',
                'status',
                'from_city',
                'to_city',
                'assignee_id',
                'created_at',
            ])
            ->withCount('packages')
            ->with(['assignee:id,name']);

        if ($this->ticketStatusFilter) {
            $q->where('status', $this->ticketStatusFilter);
        }

        if ($this->tripTypeFilter) {
            $q->where('trip_type', $this->tripTypeFilter);
        }

        if ($this->assigneeFilter) {
            $q->where('assignee_id', $this->assigneeFilter);
        }

        if ($this->searchQuery) {
            $s = trim($this->searchQuery);
            $q->where(function ($qq) use ($s) {
                $qq->where('id', 'like', "%{$s}%")
                    ->orWhere('from_city', 'like', "%{$s}%")
                    ->orWhere('to_city', 'like', "%{$s}%");
            });
        }

        return $q;
    }

    protected function getPackagesTableQuery(): Builder
    {
        $q = Package::query()
            ->select([
                'id',
                'tracking_number',
                'status',
                'pickup_city',
                'delivery_city',
                'pickup_date',
                'pickup_time',
                'delivery_date',
                'delivery_time',
                'receiver_mobile',
                'compliance_confirmed',
                'delivered_at',
                'ticket_id',
                'created_at',
            ])
            ->with(['ticket:id']);

        if ($this->packageStatusFilter) {
            $q->where('status', $this->packageStatusFilter);
        }

        if ($this->pickupCityFilter) {
            $q->where('pickup_city', $this->pickupCityFilter);
        }

        if ($this->deliveryCityFilter) {
            $q->where('delivery_city', $this->deliveryCityFilter);
        }

        if ($this->delayedFilter === 'yes') {
            $q->where('status', '!=', 'delivered')
                ->whereNotNull('delivery_date')
                ->whereNotNull('delivery_time')
                ->whereRaw(
                    "STR_TO_DATE(CONCAT(delivery_date,' ',delivery_time),'%Y-%m-%d %H:%i:%s') < ?",
                    [now()->format('Y-m-d H:i:s')]
                );
        }

        if ($this->searchQuery) {
            $s = trim($this->searchQuery);
            $q->where(function ($qq) use ($s) {
                $qq->where('tracking_number', 'like', "%{$s}%")
                    ->orWhere('receiver_mobile', 'like', "%{$s}%");
            });
        }

        return $q;
    }

    protected function getTableQuery(): Builder
    {
        return $this->activeTab === 'tickets'
            ? $this->getTicketsTableQuery()
            : $this->getPackagesTableQuery();
    }

    /** =========================
     *  Table
     *  ========================= */

    public function table(Table $table): Table
    {
        return $this->activeTab === 'tickets'
            ? $this->ticketsTable($table)
            : $this->packagesTable($table);
    }

    protected function ticketsTable(Table $table): Table
    {
        return $table
            ->query($this->getTicketsTableQuery())
            ->deferLoading()
            ->paginationPageOptions([25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('trip_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'one-way' ? 'One-way' : 'Round trip')
                    ->sortable(),

                TextColumn::make('transport_type')->label('Transport')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'matched' => 'Matched',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        default => ucfirst((string) $state),
                    })
                    ->sortable(),

                TextColumn::make('from_city')->label('From')->sortable(),
                TextColumn::make('to_city')->label('To')->sortable(),

                TextColumn::make('packages_count')
                    ->label('Linked Packages')
                    ->badge()
                    ->sortable(),

                TextColumn::make('assignee.name')->label('Assigned To')->default('—')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
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
                Tables\Actions\Action::make('showLinkedPackages')
                    ->label('Linked Packages')
                    ->icon('heroicon-o-eye')
                    ->slideOver()
                    ->form([
                        Textarea::make('packages_display')
                            ->label('Packages')
                            ->rows(16)
                            ->disabled(),
                    ])
                    ->mountUsing(function ($form, TravelerTicket $record) {
                        $list = Package::query()
                            ->select(['tracking_number', 'pickup_city', 'delivery_city'])
                            ->where('ticket_id', $record->id)
                            ->latest('id')
                            ->limit(50)
                            ->get()
                            ->map(fn ($p) => "{$p->tracking_number} | {$p->pickup_city} → {$p->delivery_city}")
                            ->implode("\n");

                        $form->fill([
                            'packages_display' => $list ?: 'No packages linked.',
                        ]);
                    })
                    ->visible(fn () => auth()->user()?->hasPermission('manage-traveler-tickets')),
            ]);
    }

    protected function packagesTable(Table $table): Table
    {
        return $table
            ->query($this->getPackagesTableQuery())
            ->deferLoading()
            ->paginationPageOptions([25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->sortable()
                    ->searchable()
                    ->copyable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending_review' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'paid' => 'Paid',
                        'in_transit' => 'In Transit',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        default => ucfirst(str_replace('_', ' ', (string) $state)),
                    })
                    ->sortable(),

                TextColumn::make('pickup_city')->label('Pickup')->sortable()->searchable(),
                TextColumn::make('delivery_city')->label('Delivery')->sortable()->searchable(),

                TextColumn::make('pickup_datetime')
                    ->label('Pickup Date')
                    ->dateTime()
                    ->getStateUsing(fn (Package $record) => $record->pickup_datetime)
                    ->sortable(query: fn (Builder $q, string $dir) => $q->orderBy('pickup_date', $dir)->orderBy('pickup_time', $dir)),

                TextColumn::make('delivery_datetime')
                    ->label('Delivery Date')
                    ->dateTime()
                    ->getStateUsing(fn (Package $record) => $record->delivery_datetime)
                    ->sortable(query: fn (Builder $q, string $dir) => $q->orderBy('delivery_date', $dir)->orderBy('delivery_time', $dir)),

                TextColumn::make('receiver_mobile')->label('Receiver Mobile')->sortable()->searchable(),

                IconColumn::make('compliance_confirmed')->label('Compliance')->boolean()->sortable(),

                TextColumn::make('ticket.id')
                    ->label('Linked Ticket')
                    ->formatStateUsing(fn ($state) => $state ? "Ticket #{$state}" : '—')
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
                /**
                 * ✅ Link package -> ticket (with traveler search)
                 */
                Tables\Actions\Action::make('linkToTicket')
                    ->label('Link to Ticket')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->visible(fn () => auth()->user()?->hasPermission('link-ticket-package'))
                    ->form([
                        Select::make('ticket_id')
                            ->label('Ticket')
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $search = trim($search);

                                return TravelerTicket::query()
                                    ->select(['id', 'traveler_id', 'from_city', 'to_city', 'status'])
                                    ->with(['traveler:id,phone'])
                                    ->where('status', 'active')
                                    ->where(function ($q) use ($search) {
                                        $q->where('id', 'like', "%{$search}%")
                                          ->orWhere('traveler_id', 'like', "%{$search}%")
                                          ->orWhere('from_city', 'like', "%{$search}%")
                                          ->orWhere('to_city', 'like', "%{$search}%")
                                          ->orWhereHas('traveler', function ($t) use ($search) {
                                              $t->where('phone', 'like', "%{$search}%");
                                          });
                                    })
                                    ->orderByDesc('id')
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function ($t) {
                                        $phone = $t->traveler?->phone ?? '-';
                                        $label = "Ticket #{$t->id} | Traveler: {$t->traveler_id} ({$phone}) | {$t->from_city} → {$t->to_city}";
                                        return [$t->id => $label];
                                    })
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $t = TravelerTicket::query()
                                    ->select(['id', 'traveler_id', 'from_city', 'to_city'])
                                    ->with(['traveler:id,phone'])
                                    ->find($value);

                                if (! $t) {
                                    return '—';
                                }

                                $phone = $t->traveler?->phone ?? '-';
                                return "Ticket #{$t->id} | Traveler: {$t->traveler_id} ({$phone}) | {$t->from_city} → {$t->to_city}";
                            })
                            ->required(),
                    ])
                    ->action(function (Package $record, array $data) {
                        $record->update(['ticket_id' => $data['ticket_id']]);

                        Notification::make()
                            ->title('Package linked to ticket successfully')
                            ->success()
                            ->send();

                        $this->resetTable();
                    }),

                Tables\Actions\Action::make('unlinkFromTicket')
                    ->label('Unlink')
                    ->icon('heroicon-o-link-slash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Package $record) => $record->ticket_id !== null && auth()->user()?->hasPermission('link-ticket-package'))
                    ->action(function (Package $record) {
                        $record->update(['ticket_id' => null]);

                        Notification::make()
                            ->title('Package unlinked successfully')
                            ->success()
                            ->send();

                        $this->resetTable();
                    }),

                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-pencil')
                    ->visible(fn () => auth()->user()?->hasPermission('manage-packages'))
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
                    ->action(function (Package $record, array $data) {
                        $record->update($data);

                        Notification::make()
                            ->title('Status updated successfully')
                            ->success()
                            ->send();

                        $this->resetTable();
                    }),

                Tables\Actions\Action::make('markDelivered')
                    ->label('Mark Delivered')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Package $record) => $record->status !== 'delivered' && auth()->user()?->hasPermission('manage-packages'))
                    ->form([
                        DatePicker::make('delivered_at')
                            ->label('Delivered At')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function (Package $record, array $data) {
                        $record->update([
                            'status' => 'delivered',
                            'delivered_at' => $data['delivered_at'],
                        ]);

                        Notification::make()
                            ->title('Package marked as delivered')
                            ->success()
                            ->send();

                        $this->resetTable();
                    }),

                Tables\Actions\Action::make('findTicketMatches')
                    ->label('Find Ticket Matches')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('primary')
                    ->slideOver()
                    ->visible(fn () => auth()->user()?->hasPermission('link-ticket-package'))
                    ->form([
                        Textarea::make('matches_display')
                            ->label('Top Matching Tickets')
                            ->rows(18)
                            ->disabled(),
                    ])
                    ->mountUsing(function ($form, Package $record) {
                        $matcher = app(TicketPackageMatcher::class);

                        $tickets = TravelerTicket::query()
                            ->select(['id', 'from_city', 'to_city', 'status', 'trip_type', 'transport_type'])
                            ->where('status', 'active')
                            ->when($record->pickup_city, fn ($q) => $q->where('from_city', $record->pickup_city))
                            ->when($record->delivery_city, fn ($q) => $q->where('to_city', $record->delivery_city))
                            ->limit(300)
                            ->get();

                        $matches = [];
                        foreach ($tickets as $t) {
                            $score = $matcher->match($record, $t);
                            if ($score > 0) {
                                $matches[] = ['ticket' => $t, 'score' => $score];
                            }
                        }

                        usort($matches, fn ($a, $b) => $b['score'] <=> $a['score']);
                        $matches = array_slice($matches, 0, 10);

                        if (! $matches) {
                            $form->fill(['matches_display' => 'No matching tickets found.']);
                            return;
                        }

                        $content = '';
                        foreach ($matches as $i => $m) {
                            $t = $m['ticket'];
                            $score = round($m['score'], 2);
                            $content .= ($i + 1) . ". Ticket #{$t->id} - Score: {$score}%\n";
                            $content .= "   Route: {$t->from_city} → {$t->to_city}\n\n";
                        }

                        $form->fill(['matches_display' => trim($content)]);
                    }),
            ]);
    }
}
