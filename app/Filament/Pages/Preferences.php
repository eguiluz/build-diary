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

    protected static ?int $navigationSort = 33;

    protected static string $view = 'filament.pages.preferences';

    public static function getNavigationLabel(): string
    {
        return __('app.preferences');
    }

    public function getTitle(): string
    {
        return __('app.preferences_page.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.preferences_page.navigation_group');
    }

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->form->fill([
            'locale' => $user->locale ?? 'es',
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
                Forms\Components\Section::make(__('app.language'))
                    ->description(__('app.language_description'))
                    ->schema([
                        Forms\Components\ToggleButtons::make('locale')
                            ->label(__('app.language'))
                            ->options([
                                'es' => 'Español',
                                'en' => 'English',
                            ])
                            ->icons([
                                'es' => 'heroicon-o-flag',
                                'en' => 'heroicon-o-flag',
                            ])
                            ->default('es')
                            ->inline()
                            ->required(),
                    ]),

                Forms\Components\Section::make(__('app.preferences_page.appearance'))
                    ->description(__('app.preferences_page.appearance_description'))
                    ->schema([
                        Forms\Components\Radio::make('theme')
                            ->label(__('app.preferences_page.theme'))
                            ->options(User::themes())
                            ->descriptions([
                                'light' => __('app.preferences_page.theme_light'),
                                'dark' => __('app.preferences_page.theme_dark'),
                                'system' => __('app.preferences_page.theme_system'),
                            ])
                            ->default('system')
                            ->required(),
                        Forms\Components\Toggle::make('sidebar_collapsed')
                            ->label(__('app.preferences_page.sidebar_collapsed'))
                            ->helperText(__('app.preferences_page.sidebar_collapsed_helper')),
                    ]),

                Forms\Components\Section::make(__('app.preferences_page.projects_section'))
                    ->description(__('app.preferences_page.projects_section_description'))
                    ->schema([
                        Forms\Components\Select::make('projects_per_page')
                            ->label(__('app.preferences_page.projects_per_page'))
                            ->options([
                                5 => '5',
                                10 => '10',
                                25 => '25',
                                50 => '50',
                            ])
                            ->default(10)
                            ->native(false),
                        Forms\Components\Toggle::make('show_completed_tasks')
                            ->label(__('app.preferences_page.show_completed_tasks'))
                            ->helperText(__('app.preferences_page.show_completed_tasks_helper')),
                    ]),

                Forms\Components\Section::make(__('app.preferences_page.notifications_section'))
                    ->description(__('app.preferences_page.notifications_section_description'))
                    ->schema([
                        Forms\Components\Toggle::make('email_notifications')
                            ->label(__('app.preferences_page.email_notifications'))
                            ->helperText(__('app.preferences_page.email_notifications_helper')),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        /** @var User $user */
        $user = Auth::user();

        // Save locale directly on user model
        $user->update([
            'locale' => $data['locale'],
            'preferences' => [
                'theme' => $data['theme'],
                'sidebar_collapsed' => $data['sidebar_collapsed'],
                'projects_per_page' => $data['projects_per_page'],
                'show_completed_tasks' => $data['show_completed_tasks'],
                'email_notifications' => $data['email_notifications'],
            ],
        ]);

        // Apply locale immediately
        app()->setLocale($data['locale']);
        session(['locale' => $data['locale']]);

        Notification::make()
            ->title(__('app.preferences_saved'))
            ->success()
            ->send();

        // Dispatch event to update theme
        $this->dispatch('theme-changed', theme: $data['theme']);

        // Redirect to refresh the page with new locale
        $this->redirect(static::getUrl());
    }

    /**
     * @return array<string, mixed>
     */
    protected function getFormActions(): array
    {
        return [];
    }
}
