<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Project;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class Gallery extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function getNavigationLabel(): string
    {
        return __('app.navigation.gallery');
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('app.navigation.gallery_title');
    }

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.gallery';

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('public_gallery')
                ->label('Ver galería pública')
                ->icon('heroicon-o-globe-alt')
                ->color('gray')
                ->url(fn () => route('public.gallery', Auth::user()))
                ->openUrlInNewTab(),
        ];
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return Project::query()
            ->where('user_id', Auth::id())
            ->with(['files' => function ($query): void {
                $query->where('type', 'image')->orderBy('order');
            }, 'status'])
            ->withCount(['files as images_count' => function ($query): void {
                $query->where('type', 'image');
            }])
            ->orderBy('updated_at', 'desc')
            ->get();
    }
}
