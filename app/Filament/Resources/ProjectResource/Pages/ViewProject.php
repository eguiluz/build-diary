<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    public function getTitle(): string
    {
        return __('app.project.time_stats_title');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('app.project.time_summary'))
                    ->schema([
                        TextEntry::make('time_total')
                            ->label(__('app.project.time_total'))
                            ->icon('heroicon-o-clock')
                            ->state(function ($record): string {
                                $minutes = (int) $record->diaryEntries()
                                    ->whereNotNull('time_spent_minutes')
                                    ->sum('time_spent_minutes');

                                if ($minutes === 0) {
                                    return '-';
                                }

                                $h = intdiv($minutes, 60);
                                $m = $minutes % 60;

                                return $h > 0 ? "{$h}h {$m}m" : "{$m}m";
                            }),

                        TextEntry::make('time_sessions')
                            ->label(__('app.project.time_sessions'))
                            ->icon('heroicon-o-document-text')
                            ->state(fn ($record): int => $record->diaryEntries()
                                ->whereNotNull('time_spent_minutes')
                                ->where('time_spent_minutes', '>', 0)
                                ->count()
                            ),

                        TextEntry::make('time_avg')
                            ->label(__('app.project.time_avg_session'))
                            ->icon('heroicon-o-calculator')
                            ->state(function ($record): string {
                                $count = $record->diaryEntries()
                                    ->whereNotNull('time_spent_minutes')
                                    ->where('time_spent_minutes', '>', 0)
                                    ->count();

                                if ($count === 0) {
                                    return '-';
                                }

                                $total = (int) $record->diaryEntries()
                                    ->whereNotNull('time_spent_minutes')
                                    ->sum('time_spent_minutes');

                                $avg = (int) round($total / $count);
                                $h = intdiv($avg, 60);
                                $m = $avg % 60;

                                return $h > 0 ? "{$h}h {$m}m" : "{$m}m";
                            }),
                    ])->columns(3),

                Section::make(__('app.project.time_by_type'))
                    ->schema([
                        ViewEntry::make('by_type')
                            ->label('')
                            ->view('filament.infolists.components.time-by-type')
                            ->columnSpanFull(),
                    ]),

                Section::make(__('app.project.time_by_week'))
                    ->schema([
                        ViewEntry::make('by_week')
                            ->label('')
                            ->view('filament.infolists.components.time-by-week')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
