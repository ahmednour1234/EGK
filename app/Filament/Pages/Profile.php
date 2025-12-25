<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.pages.profile';

    protected static ?string $navigationLabel = 'My Profile';

    protected static ?string $title = 'My Profile';

    protected static ?string $navigationGroup = 'Account';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();
        
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => '',
            'new_password' => '',
            'new_password_confirmation' => '',
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Profile Information')
                ->description('Update your account profile information')
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: auth()->user()->id),
                ]),
            Section::make('Change Password')
                ->description('Leave blank to keep current password')
                ->schema([
                    TextInput::make('current_password')
                        ->label('Current Password')
                        ->password()
                        ->revealable()
                        ->dehydrated(false),
                    TextInput::make('new_password')
                        ->label('New Password')
                        ->password()
                        ->revealable()
                        ->rules([
                            Password::min(8)
                                ->letters()
                                ->mixedCase()
                                ->numbers()
                                ->symbols(),
                        ])
                        ->dehydrated(false),
                    TextInput::make('new_password_confirmation')
                        ->label('Confirm New Password')
                        ->password()
                        ->revealable()
                        ->same('new_password')
                        ->dehydrated(false),
                ]),
        ];
    }

    protected function getForms(): array
    {
        return [
            'form' => Forms\Form::make($this)
                ->schema($this->getFormSchema())
                ->statePath('data'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save Changes')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = auth()->user();

        // Update profile information
        $user->name = $data['name'];
        $user->email = $data['email'];

        // Handle password change
        if (!empty($data['current_password']) && !empty($data['new_password'])) {
            // Verify current password
            if (!Hash::check($data['current_password'], $user->password)) {
                $this->notify('danger', 'Current password is incorrect.');
                return;
            }

            // Update password
            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        $this->notify('success', 'Profile updated successfully!');
        
        // Refresh form and clear password fields
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => '',
            'new_password' => '',
            'new_password_confirmation' => '',
        ]);
    }
}

