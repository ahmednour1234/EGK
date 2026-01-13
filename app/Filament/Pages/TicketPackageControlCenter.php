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

    public string $activeTab = 'tickets';

    public ?string $searchQuery = null;
    public ?string $packageStatusFilter = null;
    public ?string $ticketStatusFilter = null;
    public ?string $delayedFilter = null;
    public ?string $pickupCityFilter = null;
    public ?string $deliveryCityFilter = null;
    public ?string $tripTypeFilter = null;
    public ?string $assigneeFilter = null;

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;

        // مهم جدًا عشان مايفضلش ماسك pagination/filters من tab قديم
        $this->resetTable();
    }

    protected function getTicketsTableQuery(): Builder
    {
        $query = TravelerTicket::query()
            // ✅ اختار الأعمدة الأساسية بس بدل select *
            ->select([
                'id','trip_type','transport_type','status',
                'from_city','to_city','assignee_id','created_at',
            ])
            // ✅ بدل with(packages) => withCount(packages) (حل N+1 / memory)
            ->withCount('packages')
            // ✅ eager load خفيف للـ assignee فقط
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

    protected function getPackagesTableQuery(): Builder
    {
        $query = Package::query()
            ->select([
                'id','tracking_number','status',
                'pickup_city','delivery_city',
                'pickup_date','pickup_time',
                'delivery_date','delivery_time',
                'receiver_mobile','compliance_confirmed',
                'delivered_at','ticket_id','created_at',
            ])
            // ✅ حمل ticket بشكل خفيف
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

    protected function configureTicketsTable(Table $table): Table
    {
        return $table
            ->query($this->getTicketsTableQuery())
            ->deferLoading() // ✅ مهم لتقليل تجميد الصفحة
            ->paginationPageOptions([25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->searchable(),

                TextColumn::make('trip_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'one-way' ? 'One-way' : 'Round trip')
                    ->sortable(),

                TextColumn::make('transport_type')->label('Transport')->searchable()->sortable(),

                TextColumn::make('status')->badge()->sortable(),

                TextColumn::make('from_city')->label('From')->searchable()->sortable(),
                TextColumn::make('to_city')->label('To')->searchable()->sortable(),

                // ✅ بدل counts('packages') استخدم packages_count اللي جاي من withCount
                TextColumn::make('packages_count')
                    ->label('Linked Packages')
                    ->badge()
                    ->sortable(),

                TextColumn::make('assignee.name')->label('Assigned To')->default('—')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
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
            ])
            ->actions([
                Tables\Actions\Action::make('linkPackage')
                    ->label('Link Package')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->form([
                        Select::make('package_id')
                            ->label('Package')
                            // ✅ مهم: بدل pluck() لكل render
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                return Package::query()
                                    ->whereNull('ticket_id')
                                    ->where('tracking_number', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('tracking_number', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                return Package::whereKey($value)->value('tracking_number') ?? '—';
                            })
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        Package::whereKey($data['package_id'])->update(['ticket_id' => $record->id]);

                        Notification::make()->title('Package linked successfully')->success()->send();

                        // ✅ عشان packages_count يتحدث فورًا
                        $this->resetTable();
                    }),
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
                TextColumn::make('tracking_number')->label('Tracking #')->searchable()->sortable()->copyable(),
                TextColumn::make('status')->badge()->sortable(),
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
                IconColumn::make('compliance_confirmed')->label('Compliance')->boolean()->sortable(),

                TextColumn::make('ticket.id')
                    ->label('Linked Ticket')
                    ->formatStateUsing(fn ($state) => $state ? 'Ticket #' . $state : '—')
                    ->sortable(),
            ])
            ->filters([
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
            ])
            ->defaultSort('created_at', 'desc');
    }
}
