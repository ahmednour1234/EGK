<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        $arEnabled = Setting::get('ar_enabled', '1') === '1';

        $schema = [
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    Forms\Components\Select::make('slug')
                        ->options([
                            'about' => 'About Us',
                            'contact' => 'Contact Us',
                            'privacy' => 'Privacy Policy',
                        ])
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->disabled(fn ($context) => $context === 'edit'),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ]),
            Forms\Components\Section::make('English Content')
                ->schema([
                    Forms\Components\TextInput::make('title_en')
                        ->label('Title (English)')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\RichEditor::make('content_en')
                        ->label('Content (English)')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('meta_title_en')
                        ->label('Meta Title (English)')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('meta_description_en')
                        ->label('Meta Description (English)')
                        ->maxLength(65535)
                        ->rows(3),
                ]),
        ];

        // Add Arabic fields only if AR is enabled
        if ($arEnabled) {
            $schema[] = Forms\Components\Section::make('Arabic Content')
                ->schema([
                    Forms\Components\TextInput::make('title_ar')
                        ->label('Title (Arabic)')
                        ->maxLength(255),
                    Forms\Components\RichEditor::make('content_ar')
                        ->label('Content (Arabic)')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('meta_title_ar')
                        ->label('Meta Title (Arabic)')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('meta_description_ar')
                        ->label('Meta Description (Arabic)')
                        ->maxLength(65535)
                        ->rows(3),
                ]);
        }

        return $form->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('title_en')
                    ->label('Title (EN)')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('slug')
                    ->options([
                        'about' => 'About Us',
                        'contact' => 'Contact Us',
                        'privacy' => 'Privacy Policy',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}

