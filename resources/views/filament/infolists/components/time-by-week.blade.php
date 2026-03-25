@php
    use Illuminate\Support\Carbon;

    $record = $getRecord();

    $weeks = collect();
    for ($i = 7; $i >= 0; $i--) {
        $start = Carbon::now()->subWeeks($i)->startOfWeek();
        $end   = Carbon::now()->subWeeks($i)->endOfWeek();

        $minutes = (int) $record->diaryEntries()
            ->whereNotNull('time_spent_minutes')
            ->whereBetween('entry_date', [$start->toDateString(), $end->toDateString()])
            ->sum('time_spent_minutes');

        $weeks->push([
            'label'   => $start->format('d/m'),
            'minutes' => $minutes,
        ]);
    }

    $maxMinutes = $weeks->max('minutes') ?: 1;
    $maxBarPx   = 100; // height of tallest bar in px
@endphp

@if($weeks->sum('minutes') === 0)
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.project.time_no_weeks') }}</p>
@else
    <div class="space-y-3">
        {{-- Bar chart --}}
        <div class="flex items-end gap-1.5" style="height: {{ $maxBarPx }}px;">
            @foreach($weeks as $week)
                @php
                    $barPx  = $maxMinutes > 0 ? (int) round(($week['minutes'] / $maxMinutes) * $maxBarPx) : 0;
                    $h      = intdiv($week['minutes'], 60);
                    $m      = $week['minutes'] % 60;
                    $timeStr = $week['minutes'] > 0
                        ? ($h > 0 ? "{$h}h {$m}m" : "{$m}m")
                        : '';
                @endphp
                <div class="group relative flex flex-1 flex-col items-center justify-end" style="height: {{ $maxBarPx }}px;">
                    {{-- Tooltip --}}
                    @if($week['minutes'] > 0)
                        <span class="pointer-events-none absolute -top-6 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-gray-800 px-1.5 py-0.5 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100 dark:bg-gray-700">
                            {{ $timeStr }}
                        </span>
                    @endif

                    {{-- Bar --}}
                    <div
                        class="w-full rounded-t transition-all {{ $barPx > 0 ? 'bg-primary-500' : 'bg-gray-200 dark:bg-gray-700' }}"
                        style="height: {{ max($barPx, $week['minutes'] > 0 ? 3 : 0) }}px;"
                    ></div>
                </div>
            @endforeach
        </div>

        {{-- Week labels --}}
        <div class="flex gap-1.5">
            @foreach($weeks as $week)
                <div class="flex-1 text-center text-xs text-gray-400 dark:text-gray-500">
                    {{ $week['label'] }}
                </div>
            @endforeach
        </div>
    </div>
@endif
