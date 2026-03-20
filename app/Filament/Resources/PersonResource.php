<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonResource\Pages;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): ?string
    {
        return __('app.navigation.people');
    }

    public static function getModelLabel(): string
    {
        return __('app.person.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.person.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('app.person.section_personal'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('app.person.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('app.person.email'))
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('app.person.phone'))
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('birthday')
                            ->label(__('app.person.birthday')),
                    ])->columns(2),

                Forms\Components\Section::make(__('app.person.section_birthday_reminder'))
                    ->schema([
                        Forms\Components\Toggle::make('birthday_reminder')
                            ->label(__('app.person.birthday_reminder')),
                        Forms\Components\TextInput::make('reminder_days_before')
                            ->label(__('app.person.reminder_days_before'))
                            ->numeric()
                            ->default(7)
                            ->minValue(1)
                            ->maxValue(30),
                    ])->columns(2),

                Forms\Components\Section::make(__('app.person.notes'))
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label(__('app.person.notes'))
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('app.person.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('app.person.email_short'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('app.person.phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthday')
                    ->label(__('app.person.birthday'))
                    ->date('d/m')
                    ->sortable(),
                Tables\Columns\IconColumn::make('birthday_reminder')
                    ->label(__('app.person.birthday_reminder'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('app.common.created_at'))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('birthday_reminder')
                    ->label(__('app.person.filter_with_reminder')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'edit' => Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}
