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
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
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

    public string $activeTab = 'tickets';

    /**
     * ✅ keep tab in URL (fast switching + no heavy action)
     */
    protected $queryString = [
        'activeTab' => ['as' => 'tab', 'default' => 'tickets'],
    ];

    // optional UI filters (لو هتستخدمهم)
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
        return [
            \App\Filament\Widgets\TicketPackageStatsOverview::class,
        ];
    }

    public function mount(): void
    {
        $this->activeTab = request('tab', 'tickets');
    }

    public function updatedActiveTab(): void
    {
        // ✅ مهم لتفادي بطء وتراكم state
        $this->resetTable();
    }

    /**
     * =========================
     * Queries (Optimized)
     * =========================
     */
    protected function ticketsQuery(): Builder
    {
        $query = TravelerTicket::query()
            ->select([
                'id', 'trip_type', 'transport_type', 'status',
                'from_city', 'to_city', 'assignee_id', 'created_at',
            ])
            ->with([
                'assignee:id,name',
            ])
            ->withCount('packages');

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
            $s = trim($this->searchQuery);
            $query->where(function ($q) use ($s) {
                $q->where('id', 'like', "%{$s}%")
                    ->orWhere('from_city', 'like', "%{$s}%")
                    ->orWhere('to_city', 'like', "%{$s}%");
            });
        }

        return $query;
    }

    protected function packagesQuery(): Builder
    {
        $query = Package::query()
            ->select([
                'id', 'tracking_number', 'status',
                'pickup_city', 'delivery_city',
                'pickup_date', 'pickup_time',
                'delivery_date', 'delivery_time',
                'receiver_mobile',
                'compliance_confirmed',
                'delivered_at',
                'ticket_id',
                'created_at',
            ])
            ->with([
                'ticket:id',
            ]);

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
            $s = trim($this->searchQuery);
            $query->where(function ($q) use ($s) {
                $q->where('tracking_number', 'like', "%{$s}%")
                    ->orWhere('receiver_mobile', 'like', "%{$s}%");
            });
        }

        return $query;
    }

    /**
     * ✅ Filament calls this
     */
    protected function getTableQuery(): Builder
    {
        return $this->activeTab === 'tickets'
            ? $this->ticketsQuery()
            : $this->packagesQuery();
    }

    public function table(Table $table): Table
    {
        return $table
            ->deferLoading() // 🔥 huge speed win
            ->defaultPaginationPageOption(25)
            ->paginated([25, 50, 100])
            ->query($this->getTableQuery())
            ->columns(
                $this->activeTab === 'tickets'
                    ? $this->ticketsColumns()
                    : $this->packagesColumns()
            )
            ->filters(
                $this->activeTab === 'tickets'
                    ? $this->ticketsFilters()
                    : $this->packagesFilters()
            )
            ->actions(
                $this->activeTab === 'tickets'
                    ? $this->ticketsActions()
                    : $this->packagesActions()
            )
            ->defaultSort('created_at', 'desc');
    }

    /**
     * =========================
     * Columns
     * =========================
     */
    protected function ticketsColumns(): array
    {
        return [
            TextColumn::make('id')->label('ID')->sortable()->toggleable(),
            TextColumn::make('trip_type')
                ->badge()
                ->formatStateUsing(fn ($state) => $state === 'one-way' ? 'One-way' : 'Round trip')
                ->sortable(),
            TextColumn::make('transport_type')->label('Transport')->sortable(),
            TextColumn::make('status')->badge()->sortable(),
            TextColumn::make('from_city')->label('From')->sortable(),
            TextColumn::make('to_city')->label('To')->sortable(),
            TextColumn::make('packages_count')->label('Linked Packages')->badge()->sortable(),
            TextColumn::make('assignee.name')->label('Assigned To')->default('—')->sortable(),
            TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    protected function packagesColumns(): array
    {
        return [
            TextColumn::make('tracking_number')->label('Tracking #')->sortable()->copyable(),
            TextColumn::make('status')->badge()->sortable(),
            TextColumn::make('pickup_city')->label('Pickup')->sortable(),
            TextColumn::make('delivery_city')->label('Delivery')->sortable(),

            TextColumn::make('pickup_datetime')
                ->label('Pickup Date')
                ->getStateUsing(fn ($record) => $record->pickup_datetime)
                ->dateTime()
                ->sortable(query: fn (Builder $q, string $dir) => $q->orderBy('pickup_date', $dir)->orderBy('pickup_time', $dir)),

            TextColumn::make('delivery_datetime')
                ->label('Delivery Date')
                ->getStateUsing(fn ($record) => $record->delivery_datetime)
                ->dateTime()
                ->sortable(query: fn (Builder $q, string $dir) => $q->orderBy('delivery_date', $dir)->orderBy('delivery_time', $dir)),

            TextColumn::make('receiver_mobile')->label('Receiver Mobile')->sortable(),
            IconColumn::make('compliance_confirmed')->label('Compliance')->boolean()->sortable(),

            TextColumn::make('is_delayed')
                ->label('Delayed')
                ->badge()
                ->getStateUsing(fn ($record) => $record->is_delayed ? 'Yes' : 'No'),

            TextColumn::make('delivered_at')->label('Delivered At')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('ticket.id')
                ->label('Linked Ticket')
                ->formatStateUsing(fn ($state) => $state ? 'Ticket #' . $state : '—')
                ->sortable(),
        ];
    }

    /**
     * =========================
     * Filters
     * =========================
     */
    protected function ticketsFilters(): array
    {
        return [
            SelectFilter::make('status')->options([
                'draft' => 'Draft',
                'active' => 'Active',
                'matched' => 'Matched',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ]),
            SelectFilter::make('trip_type')->options([
                'one-way' => 'One-way',
                'round-trip' => 'Round trip',
            ]),
            SelectFilter::make('assignee_id')
                ->label('Assigned To')
                ->relationship('assignee', 'name')
                ->searchable()
                ->preload(),
        ];
    }

    protected function packagesFilters(): array
    {
        return [
            SelectFilter::make('status')->options([
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
        ];
    }

    /**
     * =========================
     * Actions (Optimized)
     * =========================
     */

    /**
     * ✅ بدل ما تجيب كل packages وتلف عليهم:
     * - فلتر أولي حسب المدن / type (على قد ما تقدر)
     * - limit قوي (مثلاً 300)
     * - بعدين match عليهم
     */
    protected function getMatchResults(TravelerTicket $ticket, int $limit = 300): array
    {
        $matcher = app(TicketPackageMatcher::class);

        $base = Package::query()
            ->select(['id', 'tracking_number', 'pickup_city', 'delivery_city', 'receiver_mobile'])
            ->whereIn('status', ['approved', 'paid'])
            ->where('compliance_confirmed', true)
            ->whereNull('ticket_id');

        // ✅ فلترة أولية تقلل العدد
        if ($ticket->from_city) {
            $base->where('pickup_city', $ticket->from_city);
        }
        if ($ticket->to_city) {
            $base->where('delivery_city', $ticket->to_city);
        }

        $packages = $base->limit($limit)->get();

        $matches = [];
        foreach ($packages as $package) {
            $score = $matcher->match($package, $ticket);
            if ($score > 0) {
                $matches[] = ['package' => $package, 'score' => $score];
            }
        }

        usort($matches, fn ($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($matches, 0, 10);
    }

    protected function ticketsActions(): array
    {
        return [
            Tables\Actions\Action::make('findMatches')
                ->label('Find Matches')
                ->icon('heroicon-o-magnifying-glass')
                ->color('primary')
                ->slideOver()
                ->mountUsing(function ($form, $record) {
                    /** @var TravelerTicket $record */
                    $matches = $this->getMatchResults($record);

                    if (empty($matches)) {
                        $form->fill(['matches_display' => 'No matching packages found.']);
                        return;
                    }

                    $lines = [];
                    foreach ($matches as $i => $m) {
                        $p = $m['package'];
                        $score = round($m['score'], 2);
                        $lines[] = ($i + 1) . ". {$p->tracking_number} - Score: {$score}%";
                        $lines[] = "   Route: {$p->pickup_city} → {$p->delivery_city}";
                        $lines[] = "   Receiver: {$p->receiver_mobile}";
                        $lines[] = "";
                    }

                    $form->fill(['matches_display' => trim(implode("\n", $lines))]);
                })
                ->form([
                    Forms\Components\Textarea::make('matches_display')
                        ->label('Top Matching Packages')
                        ->rows(18)
                        ->disabled(),
                ])
                ->visible(fn ($record) => $record->status === 'active' && auth()->user()?->hasPermission('link-ticket-package')),

            Tables\Actions\Action::make('linkPackage')
                ->label('Link Package')
                ->icon('heroicon-o-link')
                ->color('info')
                ->form([
                    Select::make('package_id')
                        ->label('Package')
                        ->options(fn () => Package::whereNull('ticket_id')->limit(500)->pluck('tracking_number', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    Package::whereKey($data['package_id'])->update(['ticket_id' => $record->id]);
                    Notification::make()->title('Package linked successfully')->success()->send();
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
                        ->options(fn ($record) => $record->packages()->limit(500)->pluck('tracking_number', 'id'))
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    Package::whereKey($data['package_id'])->update(['ticket_id' => null]);
                    Notification::make()->title('Package unlinked successfully')->success()->send();
                })
                ->visible(fn ($record) => $record->packages_count > 0 && auth()->user()?->hasPermission('link-ticket-package')),

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
                    Notification::make()->title('Status updated successfully')->success()->send();
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
                        ->nullable(),
                ])
                ->action(function ($record, array $data) {
                    $record->update($data);
                    Notification::make()->title('Ticket assigned successfully')->success()->send();
                })
                ->visible(fn () => auth()->user()?->hasPermission('assign-tickets')),
        ];
    }

    protected function packagesActions(): array
    {
        return [
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
                    Notification::make()->title('Status updated successfully')->success()->send();
                })
                ->visible(fn () => auth()->user()?->hasPermission('manage-packages')),

            Tables\Actions\Action::make('markDelivered')
                ->label('Mark Delivered')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->form([
                    DatePicker::make('delivered_at')->label('Delivered At')->default(now())->required(),
                ])
                ->action(function ($record, array $data) {
                    $record->update([
                        'status' => 'delivered',
                        'delivered_at' => $data['delivered_at'],
                    ]);
                    Notification::make()->title('Package marked as delivered')->success()->send();
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
        ];
    }
}
