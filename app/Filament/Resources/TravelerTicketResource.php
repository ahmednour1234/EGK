<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TravelerTicketResource\Pages;
use App\Models\TravelerTicket;
use App\Models\Sender;
use App\Models\PackageType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TravelerTicketResource extends Resource
{
    protected static ?string $model = TravelerTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Traveler Tickets';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('traveler', function ($query) {
                $query->where('type', 'traveler');
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Traveler Information')
                    ->schema([
                        Forms\Components\Select::make('traveler_id')
                            ->relationship('traveler', 'full_name', fn ($query) => $query->where('type', 'traveler'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn ($context) => $context === 'edit'),
                    ]),
                Forms\Components\Section::make('Trip Information')
                    ->schema([
                        Forms\Components\TextInput::make('from_city')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('to_city')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('full_address')
                            ->required()
                            ->rows(2),
                        Forms\Components\TextInput::make('landmark')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->label('Latitude'),
                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->label('Longitude'),
                        Forms\Components\Select::make('trip_type')
                            ->options([
                                'one-way' => 'One-way',
                                'round-trip' => 'Round trip',
                            ])
                            ->required()
                            ->native(false)
                            ->live(),
                        Forms\Components\TextInput::make('transport_type')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Car, Truck, Motorcycle'),
                        Forms\Components\DatePicker::make('departure_date')
                            ->required(),
                        Forms\Components\TimePicker::make('departure_time')
                            ->required()
                            ->seconds(false),
                        Forms\Components\DatePicker::make('return_date')
                            ->visible(fn (Forms\Get $get) => $get('trip_type') === 'round-trip')
                            ->required(fn (Forms\Get $get) => $get('trip_type') === 'round-trip'),
                        Forms\Components\TimePicker::make('return_time')
                            ->visible(fn (Forms\Get $get) => $get('trip_type') === 'round-trip')
                            ->required(fn (Forms\Get $get) => $get('trip_type') === 'round-trip')
                            ->seconds(false),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Travel Capacity')
                    ->schema([
                        Forms\Components\TextInput::make('total_weight_limit')
                            ->label('Total Weight Limit (kg)')
                            ->numeric()
                            ->step(0.01),
                        Forms\Components\TextInput::make('max_package_count')
                            ->label('Max Package Count')
                            ->numeric()
                            ->integer(),
                        Forms\Components\Select::make('acceptable_package_types')
                            ->label('Acceptable Package Types')
                            ->multiple()
                            ->options(fn () => PackageType::where('is_active', true)->pluck('name_en', 'id'))
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('preferred_pickup_area')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('preferred_delivery_area')
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Notes & Special Conditions')
                    ->schema([
                        Forms\Components\Textarea::make('notes_for_senders')
                            ->rows(4),
                        Forms\Components\Toggle::make('allow_urgent_packages')
                            ->label('Allow Urgent Packages')
                            ->default(false),
                        Forms\Components\Toggle::make('accept_only_verified_senders')
                            ->label('Accept Only Verified Senders')
                            ->default(false),
                    ]),
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Active',
                                'matched' => 'Matched',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->native(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('traveler.full_name')
                    ->label('Traveler')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('from_city')
                    ->label('From')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('to_city')
                    ->label('To')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transport_type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('trip_type')
                    ->formatStateUsing(fn ($state) => $state === 'one-way' ? 'One-way' : 'Round trip')
                    ->colors([
                        'info' => 'one-way',
                        'warning' => 'round-trip',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('departure_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departure_time')
                    ->time(),
                Tables\Columns\TextColumn::make('total_weight_limit')
                    ->label('Weight Limit (kg)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('max_package_count')
                    ->label('Max Packages')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('packages_count')
                    ->label('Linked Packages')
                    ->counts('packages')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'info' => 'active',
                        'success' => fn ($state) => in_array($state, ['matched', 'completed']),
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'matched' => 'Matched',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($state),
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('allow_urgent_packages')
                    ->label('Urgent')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('accept_only_verified_senders')
                    ->label('Verified Only')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'matched' => 'Matched',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('trip_type')
                    ->options([
                        'one-way' => 'One-way',
                        'round-trip' => 'Round trip',
                    ]),
                Tables\Filters\SelectFilter::make('transport_type')
                    ->options(fn () => TravelerTicket::distinct()->pluck('transport_type')->mapWithKeys(fn ($type) => [$type => $type])),
                Tables\Filters\SelectFilter::make('traveler_id')
                    ->label('Traveler')
                    ->relationship('traveler', 'full_name', fn ($query) => $query->where('type', 'traveler'))
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (TravelerTicket $record) {
                        $oldStatus = $record->status;
                        $record->update(['status' => 'active']);
                        \App\Events\TicketStatusChanged::dispatch($record->fresh(), $oldStatus, 'active', auth()->user());
                    })
                    ->visible(fn (TravelerTicket $record) => $record->status === 'draft')
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-traveler-tickets')),
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (TravelerTicket $record) {
                        $oldStatus = $record->status;
                        $record->update(['status' => 'cancelled']);
                        \App\Events\TicketStatusChanged::dispatch($record->fresh(), $oldStatus, 'cancelled', auth()->user());
                    })
                    ->visible(fn (TravelerTicket $record) => !in_array($record->status, ['cancelled', 'completed']))
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-traveler-tickets')),
                Tables\Actions\EditAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-traveler-tickets')),
                Tables\Actions\DeleteAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-traveler-tickets')),
                Tables\Actions\RestoreAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-traveler-tickets')),
                Tables\Actions\ForceDeleteAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-traveler-tickets')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-traveler-tickets')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-traveler-tickets')),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-traveler-tickets')),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            TravelerTicketResource\RelationManagers\PackagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTravelerTickets::route('/'),
            'create' => Pages\CreateTravelerTicket::route('/create'),
            'edit' => Pages\EditTravelerTicket::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'view-traveler-tickets') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'create-traveler-tickets') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'update-traveler-tickets') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'delete-traveler-tickets') ?? false;
    }
}
