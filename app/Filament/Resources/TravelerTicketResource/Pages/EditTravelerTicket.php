<?php

namespace App\Filament\Resources\TravelerTicketResource\Pages;

use App\Filament\Resources\TravelerTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTravelerTicket extends EditRecord
{
    protected static string $resource = TravelerTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
