<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload as FileUploadComponent;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $slug = 'app-settings';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $arEnabled = Setting::get('ar_enabled', '1') === '1';

        // Get all key-value settings (excluding system settings)
        $keyValueSettings = Setting::whereNotIn('key', ['app_name', 'app_url', 'app_logo', 'ar_enabled'])
            ->get()
            ->map(function ($setting) {
                return [
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'description' => $setting->description,
                ];
            })
            ->toArray();

        $this->form->fill([
            'app_name' => Setting::get('app_name', ''),
            'app_url' => Setting::get('app_url', ''),
            'app_email' => Setting::get('app_email', ''),
            'app_phone' => Setting::get('app_phone', ''),
            'api_base_url' => Setting::get('api_base_url', ''),
            'app_logo' => Setting::get('app_logo', ''),
            'ar_enabled' => $arEnabled,
            'key_value_settings' => $keyValueSettings,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('General Settings')
                ->schema([
                    TextInput::make('app_name')
                        ->label('App Name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('app_url')
                        ->label('App URL')
                        ->url()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('app_email')
                        ->label('App Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->helperText('Contact email address'),
                    TextInput::make('app_phone')
                        ->label('App Phone')
                        ->tel()
                        ->required()
                        ->maxLength(255)
                        ->helperText('Contact phone number'),
                    TextInput::make('api_base_url')
                        ->label('API Base URL')
                        ->url()
                        ->maxLength(255)
                        ->helperText('Base URL for API endpoints (e.g., https://api.example.com)'),
                    FileUploadComponent::make('app_logo')
                        ->label('App Logo')
                        ->image()
                        ->directory('logos')
                        ->visibility('public')
                        ->maxSize(5120),
                    Toggle::make('ar_enabled')
                        ->label('Enable Arabic Language')
                        ->helperText('When disabled, all Arabic fields will be hidden throughout the application. English will always be enabled.'),
                ]),
            Section::make('Key-Value Settings')
                ->schema([
                    Repeater::make('key_value_settings')
                        ->schema([
                            TextInput::make('key')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('value')
                                ->maxLength(65535)
                                ->rows(2),
                            Select::make('type')
                                ->options([
                                    'text' => 'Text',
                                    'url' => 'URL',
                                    'email' => 'Email',
                                    'number' => 'Number',
                                    'image' => 'Image',
                                    'file' => 'File',
                                ])
                                ->default('text')
                                ->required(),
                            Textarea::make('description')
                                ->maxLength(65535)
                                ->rows(2),
                        ])
                        ->defaultItems(0)
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['key'] ?? null),
                ]),
        ];
    }

    protected function getForms(): array
    {
        return [
            'form' => Forms\Form::make()
                ->schema($this->getFormSchema())
                ->statePath('data')
                ->model(null),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('app_name', $data['app_name'] ?? '');
        Setting::set('app_url', $data['app_url'] ?? '');
        Setting::set('app_email', $data['app_email'] ?? '');
        Setting::set('app_phone', $data['app_phone'] ?? '');
        Setting::set('api_base_url', $data['api_base_url'] ?? '', 'url');
        
        if (isset($data['app_logo']) && $data['app_logo']) {
            Setting::set('app_logo', $data['app_logo'], 'image');
        }
        
        Setting::set('ar_enabled', ($data['ar_enabled'] ?? false) ? '1' : '0');

        // Handle key-value settings
        if (isset($data['key_value_settings'])) {
            foreach ($data['key_value_settings'] as $setting) {
                Setting::set(
                    $setting['key'],
                    $setting['value'] ?? '',
                    $setting['type'] ?? 'text',
                    $setting['description'] ?? null
                );
            }
        }

        $this->notify('success', 'Settings saved successfully!');
    }
}
