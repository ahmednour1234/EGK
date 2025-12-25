<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('sender', function ($query) {
                $query->where('type', 'sender');
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Package Information')
                    ->schema([
                        Forms\Components\Select::make('sender_id')
                            ->relationship('sender', 'full_name', fn ($query) => $query->where('type', 'sender'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn ($context) => $context === 'edit'),
                        Forms\Components\TextInput::make('tracking_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn ($context) => $context === 'edit'),
                        Forms\Components\Select::make('package_type_id')
                            ->relationship('packageType', 'name_en')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending_review' => 'Pending Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'paid' => 'Paid',
                                'in_transit' => 'In Transit',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Toggle::make('compliance_confirmed')
                            ->label('Compliance Confirmed')
                            ->required(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Pickup Information')
                    ->schema([
                        Forms\Components\Select::make('pickup_address_id')
                            ->relationship('pickupAddress', 'title', fn ($query) => $query->where('sender_id', request()->get('sender_id')))
                            ->searchable()
                            ->preload()
                            ->label('Saved Address (Optional)'),
                        Forms\Components\Textarea::make('pickup_full_address')
                            ->label('Full Address')
                            ->required()
                            ->rows(2),
                        Forms\Components\TextInput::make('pickup_country')
                            ->default('Lebanon')
                            ->required(),
                        Forms\Components\TextInput::make('pickup_city')
                            ->required(),
                        Forms\Components\TextInput::make('pickup_area')
                            ->label('Area / District'),
                        Forms\Components\Textarea::make('pickup_landmark')
                            ->label('Landmark (Optional)')
                            ->rows(2),
                        Forms\Components\TextInput::make('pickup_latitude')
                            ->numeric()
                            ->label('Latitude'),
                        Forms\Components\TextInput::make('pickup_longitude')
                            ->numeric()
                            ->label('Longitude'),
                        Forms\Components\DatePicker::make('pickup_date')
                            ->required(),
                        Forms\Components\TimePicker::make('pickup_time')
                            ->required()
                            ->seconds(false),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Delivery Information')
                    ->schema([
                        Forms\Components\Textarea::make('delivery_full_address')
                            ->label('Full Address')
                            ->required()
                            ->rows(2),
                        Forms\Components\TextInput::make('delivery_country')
                            ->default('Lebanon')
                            ->required(),
                        Forms\Components\TextInput::make('delivery_city')
                            ->required(),
                        Forms\Components\TextInput::make('delivery_area')
                            ->label('Area / District'),
                        Forms\Components\Textarea::make('delivery_landmark')
                            ->label('Landmark (Optional)')
                            ->rows(2),
                        Forms\Components\TextInput::make('delivery_latitude')
                            ->numeric()
                            ->label('Latitude'),
                        Forms\Components\TextInput::make('delivery_longitude')
                            ->numeric()
                            ->label('Longitude'),
                        Forms\Components\DatePicker::make('delivery_date')
                            ->required(),
                        Forms\Components\TimePicker::make('delivery_time')
                            ->required()
                            ->seconds(false),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Receiver Information')
                    ->schema([
                        Forms\Components\TextInput::make('receiver_name')
                            ->required(),
                        Forms\Components\TextInput::make('receiver_mobile')
                            ->required()
                            ->tel(),
                        Forms\Components\Textarea::make('receiver_notes')
                            ->label('Notes (Optional)')
                            ->rows(3),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Package Details')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(3),
                        Forms\Components\TextInput::make('weight')
                            ->label('Weight (kg)')
                            ->required()
                            ->numeric()
                            ->step(0.01),
                        Forms\Components\TextInput::make('length')
                            ->label('Length (cm)')
                            ->numeric()
                            ->step(0.01),
                        Forms\Components\TextInput::make('width')
                            ->label('Width (cm)')
                            ->numeric()
                            ->step(0.01),
                        Forms\Components\TextInput::make('height')
                            ->label('Height (cm)')
                            ->numeric()
                            ->step(0.01),
                        Forms\Components\Textarea::make('special_instructions')
                            ->label('Special Instructions (Optional)')
                            ->rows(3),
                        Forms\Components\FileUpload::make('image')
                            ->label('Package Image (Optional)')
                            ->image()
                            ->directory('packages')
                            ->visibility('public')
                            ->maxSize(5120),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->label('Tracking #'),
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->label('Sender')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('packageType.name_en')
                    ->label('Package Type')
                    ->badge()
                    ->color(fn ($record) => $record->packageType?->color ?? 'gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver_name')
                    ->label('Receiver')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pickup_city')
                    ->label('Pickup')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_city')
                    ->label('Delivery')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Weight (kg)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending_review',
                        'info' => 'approved',
                        'danger' => 'rejected',
                        'success' => fn ($state) => in_array($state, ['paid', 'delivered']),
                        'primary' => 'in_transit',
                        'gray' => 'cancelled',
                    ])
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
                    ->sortable(),
                Tables\Columns\IconColumn::make('compliance_confirmed')
                    ->label('Compliance')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pickup_date')
                    ->dateTime()
                    ->label('Pickup Date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('delivery_date')
                    ->dateTime()
                    ->label('Delivery Date')
                    ->sortable()
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
                        'pending_review' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'paid' => 'Paid',
                        'in_transit' => 'In Transit',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('package_type_id')
                    ->label('Package Type')
                    ->relationship('packageType', 'name_en')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('sender_id')
                    ->label('Sender')
                    ->relationship('sender', 'full_name', fn ($query) => $query->where('type', 'sender'))
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Package $record) => $record->update(['status' => 'approved']))
                    ->visible(fn (Package $record) => $record->status === 'pending_review')
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-packages')),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Package $record) => $record->update(['status' => 'rejected']))
                    ->visible(fn (Package $record) => $record->status === 'pending_review')
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-packages')),
                Tables\Actions\EditAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-packages')),
                Tables\Actions\DeleteAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-packages')),
                Tables\Actions\RestoreAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-packages')),
                Tables\Actions\ForceDeleteAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-packages')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-packages')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-packages')),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-packages')),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'view-packages') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'create-packages') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'update-packages') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'delete-packages') ?? false;
    }
}
