<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TravelerResource\Pages;
use App\Models\Sender;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class TravelerResource extends Resource
{
    protected static ?string $model = Sender::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Access';

    protected static ?string $navigationLabel = 'Travelers';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'traveler');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Traveler Information')
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('phone')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn ($context) => $context === 'create')
                            ->minLength(8)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                        Forms\Components\FileUpload::make('avatar')
                            ->image()
                            ->directory('avatars')
                            ->visibility('public')
                            ->maxSize(5120),
                    ]),
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'sender' => 'Sender',
                                'traveler' => 'Traveler',
                            ])
                            ->required()
                            ->default('traveler')
                            ->disabled(fn ($context) => $context === 'edit'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'blocked' => 'Blocked',
                                'banned' => 'Banned',
                            ])
                            ->required()
                            ->default('inactive'),
                        Forms\Components\Toggle::make('is_verified')
                            ->label('Email Verified')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'info' => 'sender',
                        'warning' => 'traveler',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'danger' => fn ($state) => in_array($state, ['blocked', 'banned']),
                    ])
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean()
                    ->label('Verified')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
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
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'blocked' => 'Blocked',
                        'banned' => 'Banned',
                    ]),
                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Email Verified'),
            ])
            ->actions([
                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Activate Traveler')
                    ->modalDescription('Are you sure you want to activate this traveler?')
                    ->action(fn (Sender $record) => $record->update(['status' => 'active']))
                    ->visible(fn (Sender $record) => $record->status !== 'active')
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-senders')),
                Tables\Actions\Action::make('deactivate')
                    ->label('Deactivate')
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Deactivate Traveler')
                    ->modalDescription('Are you sure you want to deactivate this traveler?')
                    ->action(fn (Sender $record) => $record->update(['status' => 'inactive']))
                    ->visible(fn (Sender $record) => $record->status !== 'inactive')
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-senders')),
                Tables\Actions\Action::make('block')
                    ->label('Block')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Block Traveler')
                    ->modalDescription('Are you sure you want to block this traveler? They will not be able to access their account.')
                    ->action(fn (Sender $record) => $record->update(['status' => 'blocked']))
                    ->visible(fn (Sender $record) => $record->status !== 'blocked')
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-senders')),
                Tables\Actions\Action::make('ban')
                    ->label('Ban')
                    ->icon('heroicon-o-shield-exclamation')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Ban Traveler')
                    ->modalDescription('Are you sure you want to ban this traveler? This is a permanent action.')
                    ->action(fn (Sender $record) => $record->update(['status' => 'banned']))
                    ->visible(fn (Sender $record) => $record->status !== 'banned')
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-senders')),
                Tables\Actions\EditAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-senders')),
                Tables\Actions\DeleteAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-senders')),
                Tables\Actions\RestoreAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-senders')),
                Tables\Actions\ForceDeleteAction::make()
                    ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-senders')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'active']))
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-senders')),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'inactive']))
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-senders')),
                    Tables\Actions\BulkAction::make('block')
                        ->label('Block Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'blocked']))
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-senders')),
                    Tables\Actions\BulkAction::make('ban')
                        ->label('Ban Selected')
                        ->icon('heroicon-o-shield-exclamation')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'banned']))
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-senders')),
                    Tables\Actions\DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-senders')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'update-senders')),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()?->role?->permissions->contains('slug', 'delete-senders')),
                ]),
            ]);
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
            'index' => Pages\ListTravelers::route('/'),
            'create' => Pages\CreateTraveler::route('/create'),
            'edit' => Pages\EditTraveler::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'view-senders') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'create-senders') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'update-senders') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'delete-senders') ?? false;
    }
}
