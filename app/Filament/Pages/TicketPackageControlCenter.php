<?php

namespace App\Filament\Pages;

use App\Models\Package;
use App\Models\TravelerTicket;
use App\Services\TicketPackageMatcher;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
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

class TicketPackageControlCenter extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-command-line';
    protected static string $view = 'filament.pages.ticket-package-control-center';
    protected static ?string $navigationLabel = 'Control Center';
    protected static ?string $title = 'Ticket & Package Control Center';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 1;

    // ✅ Tabs
    public string $activeTab = 'tickets';

    // ✅ Filters (زي ما عندك)
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
            $user->hasPermission('manage-packages') ||
            $user->hasPermission('manage-traveler-tickets')
        );
    }

    protected function getHeaderWidgets(): array
    {
        // ✅ رجعنا الستاتس
        return [
            \App\Filament\Widgets\TicketPackageStatsOverview::class,
        ];
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;

        // ✅ مهم جدًا: يمنع تعلق pagination/filters ويقلل rerenders
        $this->resetTable();
    }

    /**
     * ✅ Tickets Query - خفيف جدًا (حل N+1)
     */
    protected function getTicketsTableQuery(): Builder
    {
        $query = TravelerTicket::query()
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
            // ✅ بدل with(packages) + counts(packages)
            ->withCount('packages')
            ->with(['assignee:id,name']);

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
            $s = $this->searchQuery;
            $query->where(function ($q) use ($s) {
                $q->where('id', 'like', "%{$s}%")
                    ->orWhere('from_city', 'like', "%{$s}%")
                    ->orWhere('to_city', 'like', "%{$s}%");
            });
        }

        return $query;
    }

    /**
     * ✅ Packages Query - خفيف
     */
    protected function getPackagesTableQuery(): Builder
    {
        $query = Package::query()
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
                ->whereNotNull('delivery_date')
                ->whereNotNull('delivery_time')
                ->where(DB::raw("CONCAT(delivery_date, ' ', delivery_time)"), '<', now());
        }

        if ($this->searchQuery) {
            $s = $this->searchQuery;
            $query->where(function ($q) use ($s) {
                $q->where('tracking_number', 'like', "%{$s}%")
                    ->orWhere('receiver_mobile', 'like', "%{$s}%");
            });
        }

        return $query;
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

    /**
     * ✅ Find Matches - محسنة (limit + filtering أولي)
     */
    protected function getMatchResults(TravelerTicket $ticket): array
    {
        $matcher = app(TicketPackageMatcher::class);

        $packages = Package::query()
            ->select(['id', 'tracking_number', 'pickup_city', 'delivery_city', 'receiver_mobile'])
            ->whereIn('status', ['approved', 'paid'])
            ->where('compliance_confirmed', true)
            ->whereNull('ticket_id')
            // ✅ فلترة أولية تقلل النتائج قبل الحساب
            ->when($ticket->from_city, fn ($q) => $q->where('pickup_city', $ticket->from_city))
            ->when($ticket->to_city, fn ($q) => $q->where('delivery_city', $ticket->to_city))
            ->limit(300)
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

        return array_slice($matches, 0, 10);
    }

    protected function configureTicketsTable(Table $table): Table
    {
        return $table
            ->query($this->getTicketsTableQuery())
            ->deferLoading() // ✅ فرق كبير
            ->paginationPageOptions([25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->searchable(),

                TextColumn::make('trip_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'one-way' ? 'One-way' : 'Round trip')
                    ->sortable(),

                TextColumn::make('transport_type')
                    ->label('Transport')
                    ->searchable()
                    ->sortable(),

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

                TextColumn::make('from_city')->label('From')->searchable()->sortable(),
                TextColumn::make('to_city')->label('To')->searchable()->sortable(),

                // ✅ جاي من withCount('packages')
                TextColumn::make('packages_count')
                    ->label('Linked Packages')
                    ->badge()
                    ->sortable(),

                TextColumn::make('assignee.name')
                    ->label('Assigned To')
                    ->default('—')
                    ->sortable(),

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
                // ✅ Find Matches
                Tables\Actions\Action::make('findMatches')
                    ->label('Find Matches')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('primary')
                    ->slideOver()
                    ->mountUsing(function ($form, TravelerTicket $record) {
                        $matches = $this->getMatchResults($record);

                        if (empty($matches)) {
                            $form->fill(['matches_display' => 'No matching packages found.']);
                            return;
                        }

                        $content = '';
                        foreach ($matches as $index => $match) {
                            $p = $match['package'];
                            $score = round($match['score'], 2);
                            $content .= ($index + 1) . ". {$p->tracking_number} - Score: {$score}%\n";
                            $content .= "   Route: {$p->pickup_city} → {$p->delivery_city}\n";
                            $content .= "   Receiver: {$p->receiver_mobile}\n\n";
                        }

                        $form->fill(['matches_display' => trim($content)]);
                    })
                    ->form([
                        Forms\Components\Textarea::make('matches_display')
                            ->label('Top Matching Packages')
                            ->rows(18)
                            ->disabled(),
                    ])
                    ->visible(fn ($record) => $record->status === 'active' && auth()->user()?->hasPermission('link-ticket-package')),

                // ✅ Link Package (بحث سريع بدل pluck كبيرة)
                Tables\Actions\Action::make('linkPackage')
                    ->label('Link Package')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->form([
                        Select::make('package_id')
                            ->label('Package')
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                return Package::query()
                                    ->whereNull('ticket_id')
                                    ->where('tracking_number', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('tracking_number', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn ($value) => Package::whereKey($value)->value('tracking_number') ?? '—')
                            ->required(),
                    ])
                    ->action(function (TravelerTicket $record, array $data) {
                        Package::whereKey($data['package_id'])->update(['ticket_id' => $record->id]);

                        Notification::make()->title('Package linked successfully')->success()->send();

                        $this->resetTable();
                    })
                    ->visible(fn () => auth()->user()?->hasPermission('link-ticket-package')),

                // ✅ Unlink Package (Query مباشرة بدل $record->packages)
                Tables\Actions\Action::make('unlinkPackage')
                    ->label('Unlink Package')
                    ->icon('heroicon-o-link-slash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Select::make('package_id')
                            ->label('Package to Unlink')
                            ->searchable()
                            ->options(fn (TravelerTicket $record) => Package::where('ticket_id', $record->id)->pluck('tracking_number', 'id')->toArray())
                            ->required(),
                    ])
                    ->action(function (TravelerTicket $record, array $data) {
                        Package::whereKey($data['package_id'])->update(['ticket_id' => null]);

                        Notification::make()->title('Package unlinked successfully')->success()->send();

                        $this->resetTable();
                    })
                    ->visible(fn () => auth()->user()?->hasPermission('link-ticket-package')),

                // ✅ Update Status
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
                    ->action(function (TravelerTicket $record, array $data) {
                        $record->update($data);
                        Notification::make()->title('Status updated successfully')->success()->send();
                        $this->resetTable();
                    })
                    ->visible(fn () => auth()->user()?->hasPermission('manage-traveler-tickets')),

                // ✅ Assign
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
                    ->action(function (TravelerTicket $record, array $data) {
                        $record->update($data);
                        Notification::make()->title('Ticket assigned successfully')->success()->send();
                        $this->resetTable();
                    })
                    ->visible(fn () => auth()->user()?->hasPermission('assign-tickets')),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function configurePackagesTable(Table $table): Table
    {
        return $table
            ->query($this->getPackagesTableQuery())
            ->deferLoading()
            ->paginationPageOptions([25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->searchable()
                    ->sortable()
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

                TextColumn::make('pickup_city')->label('Pickup')->searchable()->sortable(),
                TextColumn::make('delivery_city')->label('Delivery')->searchable()->sortable(),

                TextColumn::make('pickup_datetime')
                    ->label('Pickup Date')
                    ->dateTime()
                    ->getStateUsing(fn ($record) => $record->pickup_datetime)
                    ->sortable(query: fn (Builder $q, string $dir) => $q->orderBy('pickup_date', $dir)->orderBy('pickup_time', $dir)),

                TextColumn::make('delivery_datetime')
                    ->label('Delivery Date')
                    ->dateTime()
                    ->getStateUsing(fn ($record) => $record->delivery_datetime)
                    ->sortable(query: fn (Builder $q, string $dir) => $q->orderBy('delivery_date', $dir)->orderBy('delivery_time', $dir)),

                TextColumn::make('receiver_mobile')->label('Receiver Mobile')->searchable()->sortable(),

                IconColumn::make('compliance_confirmed')
                    ->label('Compliance')
                    ->boolean()
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
                    ->action(function (Package $record, array $data) {
                        $record->update($data);
                        Notification::make()->title('Status updated successfully')->success()->send();
                        $this->resetTable();
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
                    ->action(function (Package $record, array $data) {
                        $record->update([
                            'status' => 'delivered',
                            'delivered_at' => $data['delivered_at'],
                        ]);
                        Notification::make()->title('Package marked as delivered')->success()->send();
                        $this->resetTable();
                    })
                    ->visible(fn (Package $record) => $record->status !== 'delivered' && auth()->user()?->hasPermission('manage-packages')),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
