<?php

namespace App\Filament\Widgets;

use App\Models\TravelerTicket;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class NewTravelerTicketsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'New Traveler Tickets';

    protected static ?string $description = 'Recent traveler tickets that need attention';

    public static function canView(): bool
    {
        return auth()->user()?->role?->permissions->contains('slug', 'view-traveler-tickets') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TravelerTicket::query()
                    ->whereHas('traveler', function ($query) {
                        $query->where('type', 'traveler');
                    })
                    ->whereIn('status', ['draft', 'active'])
                    ->latest()
                    ->limit(10)
            )
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
                Tables\Columns\TextColumn::make('departure_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'info' => 'active',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'draft' => 'Draft',
                        'active' => 'Active',
                        default => ucfirst($state),
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

