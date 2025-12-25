<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SwitchUserType extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static string $view = 'filament.pages.switch-user-type';

    protected static ?string $title = 'Switch User Type';

    protected static ?string $navigationLabel = 'Switch Type';

    protected static ?string $navigationGroup = 'Account';

    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function switchToSender()
    {
        Session::put('user_type_view', 'sender');
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('filament.admin.auth.login')
            ->with('info', 'Switched to Sender view. Please login again.');
    }

    public function switchToTraveler()
    {
        Session::put('user_type_view', 'traveler');
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('filament.admin.auth.login')
            ->with('info', 'Switched to Traveler view. Please login again.');
    }
}

