<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

/**
 * @property Form $form
 */
class Preferences extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Preferencias';

    protected static ?string $title = 'Preferencias';

    protected static ?string $navigationGroup = 'Configuración';

    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.pages.preferences';

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->form->fill([
            'theme' => $user->getPreference('theme'),
            'sidebar_collapsed' => $user->getPreference('sidebar_collapsed'),
            'projects_per_page' => $user->getPreference('projects_per_page'),
            'show_completed_tasks' => $user->getPreference('show_completed_tasks'),
            'email_notifications' => $user->getPreference('email_notifications'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Apariencia')
                    ->description('Personaliza el aspecto visual del panel')
                    ->schema([
                        Forms\Components\Radio::make('theme')
                            ->label('Tema')
                            ->options(User::themes())
                            ->descriptions([
                                'light' => 'Siempre usar tema claro',
                                'dark' => 'Siempre usar tema oscuro',
                                'system' => 'Usar la preferencia de tu sistema operativo',
                            ])
                            ->default('system')
                            ->required(),
                        Forms\Components\Toggle::make('sidebar_collapsed')
                            ->label('Barra lateral colapsada por defecto')
                            ->helperText('Iniciar con el menú lateral minimizado'),
                    ]),

                Forms\Components\Section::make('Proyectos')
                    ->description('Configuración de la vista de proyectos')
                    ->schema([
                        Forms\Components\Select::make('projects_per_page')
                            ->label('Proyectos por página')
                            ->options([
                                5 => '5',
                                10 => '10',
                                25 => '25',
                                50 => '50',
                            ])
                            ->default(10)
                            ->native(false),
                        Forms\Components\Toggle::make('show_completed_tasks')
                            ->label('Mostrar tareas completadas')
                            ->helperText('Mostrar las tareas completadas en las listas de tareas'),
                    ]),

                Forms\Components\Section::make('Notificaciones')
                    ->description('Gestiona tus preferencias de notificaciones')
                    ->schema([
                        Forms\Components\Toggle::make('email_notifications')
                            ->label('Notificaciones por email')
                            ->helperText('Recibir recordatorios de eventos y cumpleaños por email'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        /** @var User $user */
        $user = Auth::user();

        $user->update([
            'preferences' => [
                'theme' => $data['theme'],
                'sidebar_collapsed' => $data['sidebar_collapsed'],
                'projects_per_page' => $data['projects_per_page'],
                'show_completed_tasks' => $data['show_completed_tasks'],
                'email_notifications' => $data['email_notifications'],
            ],
        ]);

        Notification::make()
            ->title('Preferencias guardadas')
            ->success()
            ->send();

        // Dispatch event to update theme
        $this->dispatch('theme-changed', theme: $data['theme']);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getFormActions(): array
    {
        return [];
    }
}
