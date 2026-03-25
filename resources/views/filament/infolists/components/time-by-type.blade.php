@php
    $record = $getRecord();

    $types = \App\Models\DiaryEntry::getTypes();

    $barColors = [
        'note'      => 'bg-gray-400',
        'progress'  => 'bg-blue-500',
        'milestone' => 'bg-green-500',
        'issue'     => 'bg-red-500',
        'solution'  => 'bg-amber-500',
    ];

    $badgeClasses = [
        'note'      => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
        'progress'  => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
        'milestone' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
        'issue'     => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
        'solution'  => 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
    ];

    $rows = $record->diaryEntries()
        ->reorder()
        ->whereNotNull('time_spent_minutes')
        ->where('time_spent_minutes', '>', 0)
        ->selectRaw('type, SUM(time_spent_minutes) as total_minutes, COUNT(*) as sessions')
        ->groupBy('type')
        ->orderByDesc('total_minutes')
        ->get();

    $totalMinutes = $rows->sum('total_minutes');
@endphp

@if($rows->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.project.time_no_data') }}</p>
@else
    <div class="space-y-4">
        @foreach($rows as $row)
            @php
                $pct = $totalMinutes > 0 ? ($row->total_minutes / $totalMinutes) * 100 : 0;
                $h   = intdiv($row->total_minutes, 60);
                $m   = $row->total_minutes % 60;
                $str = $h > 0 ? "{$h}h {$m}m" : "{$m}m";
                $sessions = (int) $row->sessions;
                $sessionLabel = $sessions === 1
                    ? __('app.project.time_session_singular')
                    : __('app.project.time_session_plural');
            @endphp
            <div>
                <div class="mb-1.5 flex items-center justify-between">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeClasses[$row->type] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $types[$row->type] ?? $row->type }}
                    </span>
                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ $sessions }} {{ $sessionLabel }}</span>
                        <span class="font-semibold text-gray-900 dark:text-white tabular-nums">{{ $str }}</span>
                        <span class="w-10 text-right tabular-nums">{{ number_format($pct, 0) }}%</span>
                    </div>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                    <div
                        class="{{ $barColors[$row->type] ?? 'bg-gray-400' }} h-2 rounded-full"
                        style="width: {{ number_format($pct, 2) }}%"
                    ></div>
                </div>
            </div>
        @endforeach
    </div>
@endif
