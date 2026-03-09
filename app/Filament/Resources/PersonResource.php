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

    protected static ?string $navigationGroup = 'Personas';

    protected static ?string $modelLabel = 'Persona';

    protected static ?string $pluralModelLabel = 'Personas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información personal')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('birthday')
                            ->label('Cumpleaños'),
                    ])->columns(2),

                Forms\Components\Section::make('Recordatorio de cumpleaños')
                    ->schema([
                        Forms\Components\Toggle::make('birthday_reminder')
                            ->label('Activar recordatorio'),
                        Forms\Components\TextInput::make('reminder_days_before')
                            ->label('Días de antelación')
                            ->numeric()
                            ->default(7)
                            ->minValue(1)
                            ->maxValue(30),
                    ])->columns(2),

                Forms\Components\Section::make('Notas')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
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
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthday')
                    ->label('Cumpleaños')
                    ->date('d/m')
                    ->sortable(),
                Tables\Columns\IconColumn::make('birthday_reminder')
                    ->label('Recordatorio')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('birthday_reminder')
                    ->label('Con recordatorio'),
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
