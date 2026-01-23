<?php

namespace App\Filament\Pages;

use App\Events\PackageUpdated;
use App\Events\TicketSenderLinked;
use App\Events\TicketStatusChanged;
use App\Jobs\SendFcmNotificationJob;
use App\Models\Notification as NotificationModel;
use App\Models\Package;
use App\Models\Sender;
use App\Models\TravelerTicket;
use App\Services\TicketPackageMatcher;
use App\Filament\Resources\TravelerTicketResource;
use App\Filament\Resources\PackageResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
use Illuminate\Support\Facades\Log;

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
        $this->resetTable();
    }

    /**
     * Normalize any text to valid UTF-8 to prevent "Malformed UTF-8" crashes in Filament/Laravel Js encoder.
     */
    protected static function safeText($value): string
    {
        if ($value === null) {
            return '—';
        }

        $s = (string) $value;

        if ($s === '') {
            return '—';
        }

        if (mb_check_encoding($s, 'UTF-8')) {
            return $s;
        }

        $enc = mb_detect_encoding($s, ['UTF-8', 'Windows-1256', 'ISO-8859-6', 'CP1252'], true);

        if ($enc && $enc !== 'UTF-8') {
            $converted = @mb_convert_encoding($s, 'UTF-8', $enc);
            if (is_string($converted) && $converted !== '' && mb_check_encoding($converted, 'UTF-8')) {
                return $converted;
            }
        }

        $fallback = @iconv('UTF-8', 'UTF-8//IGNORE', $s);
        if (is_string($fallback) && $fallback !== '' && mb_check_encoding($fallback, 'UTF-8')) {
            return $fallback;
        }

        $fallback2 = @iconv('Windows-1256', 'UTF-8//IGNORE', $s);
        if (is_string($fallback2) && $fallback2 !== '' && mb_check_encoding($fallback2, 'UTF-8')) {
            return $fallback2;
        }

        return '—';
    }

    protected function getTicketsTableQuery(): Builder
    {
        $q = TravelerTicket::query()
            ->select([
                'id',
                'traveler_id',
                'trip_type',
                'transport_type',
                'status',
                'from_city',
                'to_city',
                'assignee_id',
                'created_at',
            ])
            ->withCount('packages')
            ->with([
                'assignee:id,name',
                'traveler:id,full_name,phone',
            ]);

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

            $q->where(function (Builder $qq) use ($s) {
                $qq->where('id', 'like', "%{$s}%")
                    ->orWhere('traveler_id', 'like', "%{$s}%")
                    ->orWhere('from_city', 'like', "%{$s}%")
                    ->orWhere('to_city', 'like', "%{$s}%")
                    ->orWhereHas('traveler', function (Builder $t) use ($s) {
                        $t->where('full_name', 'like', "%{$s}%")
                          ->orWhere('phone', 'like', "%{$s}%");
                    });
            });
        }

        $q->orderBy('created_at', 'desc');

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
                'fees',
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
            $q->where(function (Builder $qq) use ($s) {
                $qq->where('tracking_number', 'like', "%{$s}%")
                    ->orWhere('receiver_mobile', 'like', "%{$s}%");
            });
        }

        $q->orderBy('created_at', 'desc');

        return $q;
    }

    protected function getTableQuery(): Builder
    {
        return $this->activeTab === 'tickets'
            ? $this->getTicketsTableQuery()
            : $this->getPackagesTableQuery();
    }

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
            ->defaultSort('traveler_id', 'asc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->url(fn (TravelerTicket $record): string => TravelerTicketResource::getUrl('edit', ['record' => $record]))
                    ->openUrlInNewTab(false),

                TextColumn::make('traveler_id')
                    ->label('Traveler ID')
                    ->sortable(),

                TextColumn::make('traveler.full_name')
                    ->label('Traveler Name')
                    ->default('—')
                    ->formatStateUsing(fn ($state) => self::safeText($state))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('traveler', fn (Builder $t) => $t->where('full_name', 'like', "%{$search}%"));
                    }),

                TextColumn::make('traveler.phone')
                    ->label('Traveler Phone')
                    ->default('—')
                    ->formatStateUsing(fn ($state) => self::safeText($state))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('traveler', fn (Builder $t) => $t->where('phone', 'like', "%{$search}%"));
                    }),

                TextColumn::make('trip_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'one-way' ? 'One-way' : 'Round trip')
                    ->sortable(),

                TextColumn::make('transport_type')
                    ->label('Transport')
                    ->formatStateUsing(fn ($state) => self::safeText($state))
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft' => 'Draft',
                        'approved' => 'Approved',
                        'active' => 'Active',
                        'matched' => 'Matched',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'rejected' => 'Rejected',
                        default => ucfirst((string) $state),
                    })
                    ->sortable(),

                TextColumn::make('from_city')
                    ->label('From')
                    ->formatStateUsing(fn ($state) => self::safeText($state))
                    ->sortable(),

                TextColumn::make('to_city')
                    ->label('To')
                    ->formatStateUsing(fn ($state) => self::safeText($state))
                    ->sortable(),

                TextColumn::make('packages_count')
                    ->label('Linked Packages')
                    ->badge()
                    ->sortable(),

                TextColumn::make('assignee.name')
                    ->label('Assigned To')
                    ->default('—')
                    ->formatStateUsing(fn ($state) => self::safeText($state))
                    ->sortable(),

                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'approved' => 'Approved',
                        'active' => 'Active',
                        'matched' => 'Matched',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'rejected' => 'Rejected',
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
                Tables\Actions\Action::make('active')
                    ->label('Activve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (TravelerTicket $record) => $record->status !== 'active' && auth()->user()?->hasPermission('manage-traveler-tickets'))
                    ->action(function (TravelerTicket $record) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => 'active',
                            'decided_by' => auth()->id(),
                            'decided_at' => now(),
                        ]);

                        TicketStatusChanged::dispatch($record->fresh(), $oldStatus, 'approved', auth()->user());

                        Notification::make()
                            ->title('Ticket active successfully')
                            ->success()
                            ->send();

                        $this->resetTable();
                    }),


                Tables\Actions\Action::make('linkSender')
                    ->label('Link Sender')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->form([
                        Select::make('sender_id')
                            ->label('Sender')
                            ->relationship('sender', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->visible(fn () => auth()->user()?->hasPermission('manage-traveler-tickets'))
                    ->action(function (TravelerTicket $record, array $data) {
                        $sender = Sender::findOrFail($data['sender_id']);
                        $record->update(['sender_id' => $sender->id]);

                        TicketSenderLinked::dispatch($record->fresh(), $sender);

                        Notification::make()
                            ->title('Sender linked successfully')
                            ->success()
                            ->send();

                        $this->resetTable();
                    }),

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
                            ->map(function ($p) {
                                $tracking = self::safeText($p->tracking_number);
                                $pickup = self::safeText($p->pickup_city);
                                $delivery = self::safeText($p->delivery_city);
                                return "{$tracking} | {$pickup} → {$delivery}";
                            })
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
                    ->formatStateUsing(fn ($state) => self::safeText($state))
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->url(fn (Package $record): string => PackageResource::getUrl('edit', ['record' => $record]))
                    ->openUrlInNewTab(false),

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

                TextColumn::make('pickup_city')
                    ->label('Pickup')
                    ->formatStateUsing(fn ($state) => self::safeText($state))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('delivery_city')
                    ->label('Delivery')
                    ->formatStateUsing(fn ($state) => self::safeText($state))
                    ->sortable()
                    ->searchable(),

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

                TextColumn::make('receiver_mobile')
                    ->label('Receiver Mobile')
                    ->formatStateUsing(fn ($state) => self::safeText($state))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('fees')
                    ->label('Fees')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('compliance_confirmed')->label('Compliance')->boolean()->sortable(),

                TextColumn::make('ticket.id')
                    ->label('Linked Ticket')
                    ->formatStateUsing(fn ($state) => $state ? "Ticket #{$state}" : '—')
                    ->sortable()
                    ->url(fn (Package $record): ?string => $record->ticket_id ? TravelerTicketResource::getUrl('edit', ['record' => $record->ticket_id]) : null)
                    ->openUrlInNewTab(false),
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
                Tables\Actions\Action::make('linkToTicket')
                    ->label(fn (Package $record) => $record->ticket_id ? 'Edit Link / Fees' : 'Link to Ticket')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->visible(fn () => auth()->user()?->hasPermission('link-ticket-package'))
                    ->fillForm(fn (Package $record) => [
                        'ticket_id' => $record->ticket_id,
                        'fees' => $record->fees,
                    ])
                    ->form([
                        Select::make('ticket_id')
                            ->label('Ticket')
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $search = trim($search);

                                return TravelerTicket::query()
                                    ->select(['id', 'traveler_id', 'from_city', 'to_city', 'status'])
                                    ->with(['traveler:id,full_name,phone'])
                                    ->where('status', 'active')
                                    ->where(function (Builder $q) use ($search) {
                                        $q->where('id', 'like', "%{$search}%")
                                          ->orWhere('traveler_id', 'like', "%{$search}%")
                                          ->orWhere('from_city', 'like', "%{$search}%")
                                          ->orWhere('to_city', 'like', "%{$search}%")
                                          ->orWhereHas('traveler', function (Builder $t) use ($search) {
                                              $t->where('full_name', 'like', "%{$search}%")
                                                ->orWhere('phone', 'like', "%{$search}%");
                                          });
                                    })
                                    ->orderBy('traveler_id', 'asc')
                                    ->orderByDesc('id')
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function ($t) {
                                        $name = self::safeText($t->traveler?->full_name ?? '-');
                                        $phone = self::safeText($t->traveler?->phone ?? '-');
                                        $from = self::safeText($t->from_city);
                                        $to = self::safeText($t->to_city);
                                        $label = "Ticket #{$t->id} | {$name} ({$phone}) | {$from} → {$to}";
                                        return [$t->id => $label];
                                    })
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $t = TravelerTicket::query()
                                    ->select(['id', 'traveler_id', 'from_city', 'to_city'])
                                    ->with(['traveler:id,full_name,phone'])
                                    ->find($value);

                                if (! $t) {
                                    return '—';
                                }

                                $name = self::safeText($t->traveler?->full_name ?? '-');
                                $phone = self::safeText($t->traveler?->phone ?? '-');
                                $from = self::safeText($t->from_city);
                                $to = self::safeText($t->to_city);

                                return "Ticket #{$t->id} | {$name} ({$phone}) | {$from} → {$to}";
                            })
                            ->required(),

                        TextInput::make('fees')
                            ->label('Fees')
                            ->numeric()
                            ->minValue(0)
                            ->step('0.01')
                            ->prefix('$')
                            ->default(0),
                    ])
                    ->action(function (Package $record, array $data) {
                        $record->update([
                            'ticket_id' => $data['ticket_id'],
                            'fees' => $data['fees'] ?? 0,
                        ]);

                        $ticket = TravelerTicket::find($data['ticket_id']);

                        if ($ticket) {
                            Log::info('Package linked to ticket', [
                                'package_id' => $record->id,
                                'tracking_number' => (string) $record->tracking_number,
                                'ticket_id' => $ticket->id,
                                'traveler_id' => $ticket->traveler_id,
                                'sender_id' => $ticket->sender_id,
                            ]);

                            $tracking = self::safeText($record->tracking_number);
                            $title = self::safeText('Package Linked to Ticket');
                            $body = self::safeText("Package {$tracking} has been linked to ticket #{$ticket->id}");

                            $notificationData = [
                                'type' => 'package.linked_ticket',
                                'entity' => 'package',
                                'entity_id' => $record->id,
                                'ticket_id' => $ticket->id,
                                'action' => 'linked_ticket',
                                'deep_link' => "app://package/{$record->id}",
                            ];

                            if ($ticket->traveler_id) {
                                try {
                                    NotificationModel::create([
                                        'sender_id' => $ticket->traveler_id,
                                        'type' => 'package.linked_ticket',
                                        'title' => $title,
                                        'body' => $body,
                                        'data' => $notificationData,
                                        'entity' => 'package',
                                        'entity_id' => $record->id,
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Failed to create notification for traveler', [
                                        'traveler_id' => $ticket->traveler_id,
                                        'package_id' => $record->id,
                                        'error' => $e->getMessage(),
                                    ]);
                                }
                                SendFcmNotificationJob::dispatch($ticket->traveler_id, $title, $body, $notificationData);
                            }

                            if ($ticket->sender_id) {
                                try {
                                    NotificationModel::create([
                                        'sender_id' => $ticket->sender_id,
                                        'type' => 'package.linked_ticket',
                                        'title' => $title,
                                        'body' => $body,
                                        'data' => $notificationData,
                                        'entity' => 'package',
                                        'entity_id' => $record->id,
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Failed to create notification for sender', [
                                        'sender_id' => $ticket->sender_id,
                                        'package_id' => $record->id,
                                        'error' => $e->getMessage(),
                                    ]);
                                }
                                SendFcmNotificationJob::dispatch($ticket->sender_id, $title, $body, $notificationData);
                            }
                        } else {
                            Log::warning('Ticket not found when linking package', [
                                'package_id' => $record->id,
                                'ticket_id' => $data['ticket_id'],
                            ]);
                        }

                        Notification::make()
                            ->title('Package linked & fees saved successfully')
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
                        $oldTicket = $record->ticket;
                        $oldTicketId = $oldTicket?->id;
                        $oldTicketTravelerId = $oldTicket?->traveler_id;
                        $oldTicketSenderId = $oldTicket?->sender_id;

                        $record->update(['ticket_id' => null]);

                        if ($oldTicket && $oldTicketId) {
                            $tracking = self::safeText($record->tracking_number);
                            $title = self::safeText('Package Unlinked from Ticket');
                            $body = self::safeText("Package {$tracking} has been unlinked from ticket #{$oldTicketId}");

                            $notificationData = [
                                'type' => 'package.unlinked_ticket',
                                'entity' => 'package',
                                'entity_id' => $record->id,
                                'ticket_id' => $oldTicketId,
                                'action' => 'unlinked_ticket',
                                'deep_link' => "app://package/{$record->id}",
                            ];

                            if ($oldTicketTravelerId) {
                                try {
                                    NotificationModel::create([
                                        'sender_id' => $oldTicketTravelerId,
                                        'type' => 'package.unlinked_ticket',
                                        'title' => $title,
                                        'body' => $body,
                                        'data' => $notificationData,
                                        'entity' => 'package',
                                        'entity_id' => $record->id,
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Failed to create notification for traveler', [
                                        'traveler_id' => $oldTicketTravelerId,
                                        'package_id' => $record->id,
                                        'error' => $e->getMessage(),
                                    ]);
                                }
                                SendFcmNotificationJob::dispatch($oldTicketTravelerId, $title, $body, $notificationData);
                            }

                            if ($oldTicketSenderId) {
                                try {
                                    NotificationModel::create([
                                        'sender_id' => $oldTicketSenderId,
                                        'type' => 'package.unlinked_ticket',
                                        'title' => $title,
                                        'body' => $body,
                                        'data' => $notificationData,
                                        'entity' => 'package',
                                        'entity_id' => $record->id,
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Failed to create notification for sender', [
                                        'sender_id' => $oldTicketSenderId,
                                        'package_id' => $record->id,
                                        'error' => $e->getMessage(),
                                    ]);
                                }
                                SendFcmNotificationJob::dispatch($oldTicketSenderId, $title, $body, $notificationData);
                            }
                        }

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
                        $oldStatus = $record->status;
                        $record->update($data);

                        PackageUpdated::dispatch($record->fresh(), ['status' => ['old' => $oldStatus, 'new' => $data['status']]]);

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
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => 'delivered',
                            'delivered_at' => $data['delivered_at'],
                        ]);

                        PackageUpdated::dispatch($record->fresh(), ['status' => ['old' => $oldStatus, 'new' => 'delivered']]);

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
                            $from = self::safeText($t->from_city);
                            $to = self::safeText($t->to_city);

                            $content .= ($i + 1) . ". Ticket #{$t->id} - Score: {$score}%\n";
                            $content .= "   Route: {$from} → {$to}\n\n";
                        }

                        $form->fill(['matches_display' => trim($content)]);
                    }),
            ]);
    }
}
