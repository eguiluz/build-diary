<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ __('app.public.book_title', ['name' => $user->name]) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #1e293b;
        }

        /* ===== COVER ===== */
        .cover {
            width: 100%;
            height: 100%;
            min-height: 297mm;
            background-color: #0f172a;
            padding: 0;
            position: relative;
            page-break-after: always;
        }

        .cover-top-bar {
            background: #f59e0b;
            height: 8px;
            width: 100%;
        }

        .cover-body {
            padding: 60px 50px;
        }

        .cover-logo {
            color: #f59e0b;
            font-size: 13px;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 80px;
        }

        .cover-title {
            font-size: 42px;
            color: #ffffff;
            font-weight: bold;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .cover-subtitle {
            font-size: 16px;
            color: #94a3b8;
            margin-bottom: 60px;
        }

        .cover-divider {
            width: 60px;
            height: 3px;
            background: #f59e0b;
            margin-bottom: 40px;
        }

        .cover-meta {
            color: #64748b;
            font-size: 12px;
            line-height: 2;
        }

        .cover-meta strong {
            color: #cbd5e1;
        }

        .cover-bottom {
            position: absolute;
            bottom: 40px;
            left: 50px;
            right: 50px;
            border-top: 1px solid #1e293b;
            padding-top: 20px;
        }

        .cover-bottom-text {
            color: #475569;
            font-size: 10px;
            letter-spacing: 1px;
        }

        /* ===== TABLE OF CONTENTS ===== */
        .toc {
            padding: 50px 50px 40px;
            page-break-after: always;
        }

        .toc-heading {
            font-size: 24px;
            color: #0f172a;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .toc-subtitle {
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 35px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f59e0b;
        }

        .toc-item {
            display: table;
            width: 100%;
            padding: 10px 0;
            border-bottom: 1px dotted #e2e8f0;
        }

        .toc-item-number {
            display: table-cell;
            width: 36px;
            font-size: 11px;
            color: #f59e0b;
            font-weight: bold;
            vertical-align: middle;
        }

        .toc-item-body {
            display: table-cell;
            vertical-align: middle;
        }

        .toc-item-title {
            font-size: 12px;
            color: #1e293b;
            font-weight: 600;
        }

        .toc-item-meta {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .toc-item-status {
            display: table-cell;
            width: 80px;
            text-align: right;
            vertical-align: middle;
        }

        .toc-status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
        }

        /* ===== CHAPTER ===== */
        .chapter {
            padding: 0;
            page-break-before: always;
        }

        .chapter-header {
            padding: 40px 50px 30px;
            border-bottom: 2px solid #f59e0b;
            margin-bottom: 30px;
        }

        .chapter-number {
            font-size: 10px;
            color: #f59e0b;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .chapter-title {
            font-size: 28px;
            color: #0f172a;
            font-weight: bold;
            line-height: 1.2;
            margin-bottom: 15px;
        }

        .chapter-badges {
            margin-bottom: 12px;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            margin-right: 6px;
        }

        .chapter-dates {
            font-size: 10px;
            color: #64748b;
        }

        .chapter-dates span {
            margin-right: 16px;
        }

        /* ===== CHAPTER BODY ===== */
        .chapter-body {
            padding: 0 50px 50px;
        }

        .section {
            margin-bottom: 28px;
        }

        .section-title {
            font-size: 15px;
            color: #0f172a;
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin-bottom: 14px;
        }

        .tags { margin-bottom: 14px; }

        .tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            margin-right: 4px;
        }

        /* Description */
        .description { line-height: 1.75; color: #334155; }
        .description h2 { font-size: 14px; color: #1e293b; margin: 14px 0 8px; }
        .description h3 { font-size: 12px; color: #334155; margin: 10px 0 6px; }
        .description p { margin-bottom: 8px; }
        .description ul, .description ol { margin-left: 16px; margin-bottom: 8px; }
        .description li { margin-bottom: 3px; }
        .description strong { font-weight: 600; }
        .description em { font-style: italic; }
        .description blockquote { border-left: 3px solid #f59e0b; padding-left: 12px; color: #64748b; font-style: italic; margin: 8px 0; }
        .description code { background: #f1f5f9; padding: 1px 4px; font-size: 10px; border-radius: 2px; }

        /* Gallery */
        .gallery-grid { width: 100%; }
        .gallery-item {
            display: inline-block;
            width: 48%;
            margin-right: 2%;
            margin-bottom: 12px;
            vertical-align: top;
        }
        .gallery-item:nth-child(2n) { margin-right: 0; }
        .gallery-item img {
            width: 100%;
            height: auto;
            border: 1px solid #e2e8f0;
        }
        .gallery-caption { font-size: 9px; color: #94a3b8; text-align: center; margin-top: 3px; }

        /* Dedicated to */
        .dedicated {
            display: table;
            width: 100%;
            background: #fffbeb;
            border-left: 3px solid #f59e0b;
            padding: 10px 14px;
            margin-bottom: 20px;
        }
        .dedicated-avatar {
            display: table-cell;
            width: 36px;
            vertical-align: middle;
        }
        .dedicated-avatar-inner {
            width: 30px;
            height: 30px;
            background: #f59e0b;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-size: 13px;
            font-weight: bold;
            color: #fff;
        }
        .dedicated-text { display: table-cell; vertical-align: middle; }
        .dedicated-label { font-size: 9px; color: #92400e; text-transform: uppercase; letter-spacing: 1px; }
        .dedicated-name { font-size: 13px; font-weight: 600; color: #78350f; }

        /* Checklist */
        .checklist-progress {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            margin-bottom: 12px;
        }
        .progress-bar {
            background: #e2e8f0;
            height: 6px;
            margin-top: 6px;
            overflow: hidden;
        }
        .progress-bar-fill { height: 100%; }
        .task-item { padding: 6px 0; border-bottom: 1px solid #f8fafc; font-size: 10px; }
        .task-item:last-child { border-bottom: none; }
        .task-completed { text-decoration: line-through; color: #94a3b8; }

        /* Budget */
        .budget-summary { display: table; width: 100%; margin-bottom: 16px; }
        .budget-card {
            display: table-cell;
            width: 33.33%;
            padding: 10px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        .budget-card-label { font-size: 9px; color: #64748b; text-transform: uppercase; margin-bottom: 4px; }
        .budget-card-value { font-size: 15px; font-weight: bold; }

        .expense-table { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 10px; }
        .expense-table th { background: #f1f5f9; padding: 6px 8px; text-align: left; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
        .expense-table td { padding: 6px 8px; border-bottom: 1px solid #f8fafc; }
        .expense-table tr:last-child td { border-bottom: none; }
        .expense-purchased td { color: #94a3b8; text-decoration: line-through; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .expense-category {
            font-size: 8px;
            padding: 1px 5px;
            border-radius: 3px;
            display: inline-block;
        }
        .category-material { background: #dbeafe; color: #1d4ed8; }
        .category-tool { background: #ffedd5; color: #c2410c; }
        .category-consumable { background: #f1f5f9; color: #475569; }
        .category-service { background: #dcfce7; color: #16a34a; }
        .category-other { background: #f5f5f4; color: #78716c; }

        /* Links */
        .link-item { margin-bottom: 10px; padding-left: 14px; position: relative; }
        .link-item::before { content: '→'; position: absolute; left: 0; color: #f59e0b; }
        .link-title { color: #d97706; font-weight: 500; }
        .link-url { color: #94a3b8; font-size: 9px; }
        .link-desc { color: #64748b; font-size: 10px; margin-top: 2px; }

        /* Files */
        .file-item { margin-bottom: 8px; padding-left: 14px; position: relative; }
        .file-item::before { content: '◎'; position: absolute; left: 0; color: #f59e0b; font-size: 9px; top: 1px; }
        .file-name { font-weight: 500; }
        .file-meta { color: #94a3b8; font-size: 9px; }

        /* Diary */
        .timeline-entry {
            border-left: 2px solid #f59e0b;
            padding-left: 16px;
            padding-bottom: 20px;
            margin-left: 8px;
            position: relative;
        }
        .timeline-entry:last-child { padding-bottom: 0; }
        .timeline-entry::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 2px;
            width: 8px;
            height: 8px;
            background: #f59e0b;
            border-radius: 50%;
        }
        .entry-date { font-size: 10px; color: #f59e0b; font-weight: 600; margin-bottom: 4px; }
        .entry-title { font-size: 12px; font-weight: 600; color: #1e293b; margin-bottom: 6px; }
        .entry-type {
            display: inline-block;
            font-size: 8px;
            padding: 1px 6px;
            border-radius: 8px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }
        .entry-type-progress { background: #dbeafe; color: #1d4ed8; }
        .entry-type-issue { background: #fee2e2; color: #dc2626; }
        .entry-type-solution { background: #dcfce7; color: #16a34a; }
        .entry-type-milestone { background: #f3e8ff; color: #9333ea; }
        .entry-type-note { background: #f1f5f9; color: #475569; }
        .entry-content { font-size: 10px; line-height: 1.65; color: #334155; }
        .entry-content p { margin-bottom: 6px; }
        .entry-content ul, .entry-content ol { margin-left: 14px; margin-bottom: 6px; }
        .entry-time { font-size: 9px; color: #94a3b8; margin-top: 6px; }

        /* Chapter separator */
        .chapter-footer {
            padding: 20px 50px;
            text-align: center;
            font-size: 9px;
            color: #e2e8f0;
            border-top: 1px solid #f1f5f9;
            margin-top: 20px;
        }

        .page-break { page-break-after: always; }
    </style>
</head>

<body>

{{-- ===== PORTADA ===== --}}
<div class="cover">
    <div class="cover-top-bar"></div>
    <div class="cover-body">
        <div class="cover-logo">📔 Build Diary</div>

        <div class="cover-title">{{ __('app.public.book_cover_title') }}</div>
        <div class="cover-subtitle">{{ $user->name }}</div>

        <div class="cover-divider"></div>

        <div class="cover-meta">
            <strong>{{ $projects->count() }}</strong> {{ trans_choice('app.public.book_projects_count', $projects->count(), ['count' => $projects->count()]) }}<br>
            {{ __('app.public.book_generated_on', ['date' => now()->format('d \d\e F \d\e Y')]) }}
            @if ($projects->sum(fn ($p) => $p->diaryEntries->count()) > 0)
                <br>{{ $projects->sum(fn ($p) => $p->diaryEntries->count()) }} {{ __('app.public.book_diary_entries') }}
            @endif
        </div>
    </div>

    <div class="cover-bottom">
        <div class="cover-bottom-text">BUILD DIARY · {{ config('app.url') }}</div>
    </div>
</div>

{{-- ===== ÍNDICE ===== --}}
<div class="toc">
    <div class="toc-heading">{{ __('app.public.book_toc') }}</div>
    <div class="toc-subtitle">{{ $projects->count() }} {{ strtolower(trans_choice('app.public.book_projects_count', $projects->count(), ['count' => $projects->count()])) }}</div>

    @foreach ($projects as $i => $project)
        <div class="toc-item">
            <div class="toc-item-number">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
            <div class="toc-item-body">
                <div class="toc-item-title">{{ $project->title }}</div>
                <div class="toc-item-meta">
                    @if ($project->category) {{ $project->category->name }} · @endif
                    @if ($project->started_at) {{ $project->started_at->format('Y') }} @endif
                    @if ($project->diaryEntries->count() > 0)
                        · {{ $project->diaryEntries->count() }} {{ __('app.public.book_diary_entries') }}
                    @endif
                </div>
            </div>
            @if ($project->status)
                <div class="toc-item-status">
                    <span class="toc-status-badge" style="background-color: {{ $project->status->color }}20; color: {{ $project->status->color }};">
                        {{ $project->status->name }}
                    </span>
                </div>
            @endif
        </div>
    @endforeach
</div>

{{-- ===== CAPÍTULOS ===== --}}
@foreach ($projects as $i => $project)
<div class="chapter">

    {{-- Cabecera del capítulo --}}
    <div class="chapter-header">
        <div class="chapter-number">{{ __('app.public.book_chapter') }} {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
        <div class="chapter-title">{{ $project->title }}</div>

        <div class="chapter-badges">
            @if ($project->status)
                <span class="badge" style="background-color: {{ $project->status->color }}20; color: {{ $project->status->color }};">
                    {{ $project->status->name }}
                </span>
            @endif
            @if ($project->category)
                <span class="badge" style="background-color: {{ $project->category->color ?? '#64748b' }}20; color: {{ $project->category->color ?? '#64748b' }};">
                    {{ $project->category->name }}
                </span>
            @endif
            @if ($project->priority && $project->priority > 0)
                @php
                    $pc = match($project->priority) { 1=>'#22c55e', 2=>'#f59e0b', 3=>'#ef4444', default=>'#64748b' };
                    $pl = match($project->priority) { 1=>__('app.public.priority_low'), 2=>__('app.public.priority_medium'), 3=>__('app.public.priority_high'), default=>'' };
                @endphp
                <span class="badge" style="background-color: {{ $pc }}20; color: {{ $pc }};">{{ $pl }}</span>
            @endif
            @if ($project->tags->count() > 0)
                @foreach ($project->tags as $tag)
                    <span class="tag" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }};">{{ $tag->name }}</span>
                @endforeach
            @endif
        </div>

        <div class="chapter-dates">
            @if ($project->started_at)
                <span>📅 {{ __('app.public.started') }}: {{ $project->started_at->format('d/m/Y') }}</span>
            @endif
            @if ($project->due_date)
                <span>⏰ {{ __('app.public.due_date') }}: {{ $project->due_date->format('d/m/Y') }}</span>
            @endif
            @if ($project->completed_at)
                <span>✅ {{ __('app.public.completed') }}: {{ $project->completed_at->format('d/m/Y') }}</span>
            @endif
        </div>
    </div>

    <div class="chapter-body">

        {{-- Dedicatoria --}}
        @if ($project->person)
            <div class="dedicated">
                <div class="dedicated-avatar">
                    <div class="dedicated-avatar-inner">{{ strtoupper(substr($project->person->name, 0, 1)) }}</div>
                </div>
                <div class="dedicated-text">
                    <div class="dedicated-label">
                        @if ($project->person_reason) {{ $project->person_reason_label }} {{ __('app.public.for') }}
                        @else {{ __('app.public.dedicated_to') }} @endif
                    </div>
                    <div class="dedicated-name">{{ $project->person->name }}</div>
                </div>
            </div>
        @endif

        {{-- Galería --}}
        @if ($project->files->where('type', 'image')->count() > 0)
            <div class="section">
                <div class="section-title">📷 {{ __('app.public.image_gallery') }}</div>
                <div class="gallery-grid">
                    @foreach ($project->files->where('type', 'image')->take(6) as $file)
                        <div class="gallery-item">
                            <img src="{{ Storage::disk($file->disk)->path($file->path) }}" alt="{{ $file->description ?? $file->original_name }}">
                            @if ($file->description)
                                <div class="gallery-caption">{{ $file->description }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Descripción --}}
        @if ($project->description)
            <div class="section">
                <div class="section-title">{{ __('app.public.description') }}</div>
                <div class="description">
                    {!! Str::markdown($project->description, ['renderer' => ['soft_break' => "<br>\n"]]) !!}
                </div>
            </div>
        @endif

        {{-- Checklist --}}
        @if ($project->tasks->count() > 0)
            @php
                $done = $project->tasks->where('is_completed', true)->count();
                $total = $project->tasks->count();
                $pct = $total > 0 ? round(($done / $total) * 100) : 0;
            @endphp
            <div class="section">
                <div class="section-title">📋 {{ __('app.public.checklist') }}</div>
                <div class="checklist-progress">
                    <span style="font-weight:600;">{{ __('app.public.tasks_completed', ['completed' => $done, 'total' => $total]) }}</span>
                    <span style="float:right; color:{{ $pct===100?'#22c55e':'#f59e0b' }}; font-weight:600;">{{ $pct }}%</span>
                    <div class="progress-bar">
                        <div class="progress-bar-fill" style="width:{{ $pct }}%; background:{{ $pct===100?'#22c55e':'#f59e0b' }};"></div>
                    </div>
                </div>
                @foreach ($project->tasks as $task)
                    <div class="task-item {{ $task->is_completed ? 'task-completed' : '' }}">
                        {{ $task->is_completed ? '✓' : '○' }} {{ $task->title }}
                        @if ($task->description)
                            <span style="color:#94a3b8; font-size:9px;"> — {{ Str::limit($task->description, 60) }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Presupuesto --}}
        @if ($project->expenses->count() > 0)
            <div class="section">
                <div class="section-title">💰 {{ __('app.public.budget') }}</div>
                <div class="budget-summary">
                    <div class="budget-card" style="margin-right:6px;">
                        <div class="budget-card-label">{{ __('app.public.total') }}</div>
                        <div class="budget-card-value" style="color:#1e293b;">{{ number_format($project->total_budget, 2, ',', '.') }} €</div>
                    </div>
                    <div class="budget-card" style="margin-right:6px;">
                        <div class="budget-card-label">{{ __('app.public.spent') }}</div>
                        <div class="budget-card-value" style="color:#22c55e;">{{ number_format($project->spent_budget, 2, ',', '.') }} €</div>
                    </div>
                    <div class="budget-card">
                        <div class="budget-card-label">{{ __('app.public.pending') }}</div>
                        <div class="budget-card-value" style="color:#f59e0b;">{{ number_format($project->pending_budget, 2, ',', '.') }} €</div>
                    </div>
                </div>
                <table class="expense-table">
                    <thead>
                        <tr>
                            <th style="width:5%;"></th>
                            <th style="width:38%;">{{ __('app.public.expense_item') }}</th>
                            <th style="width:18%;">{{ __('app.public.expense_category_header') }}</th>
                            <th style="width:18%;">{{ __('app.public.expense_quantity') }}</th>
                            <th class="text-right" style="width:21%;">{{ __('app.public.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($project->expenses as $expense)
                            <tr class="{{ $expense->is_purchased ? 'expense-purchased' : '' }}">
                                <td>{{ $expense->is_purchased ? '✓' : '○' }}</td>
                                <td>
                                    {{ $expense->name }}
                                    @if ($expense->supplier)
                                        <span style="color:#94a3b8; font-size:9px;"> — {{ $expense->supplier }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php $cc = match($expense->category) { 'material'=>'category-material','tool'=>'category-tool','consumable'=>'category-consumable','service'=>'category-service',default=>'category-other' }; @endphp
                                    <span class="expense-category {{ $cc }}">{{ $expense->category_label }}</span>
                                </td>
                                <td>{{ $expense->quantity }}{{ $expense->unit ? ' '.$expense->unit : '' }}</td>
                                <td class="text-right font-bold">{{ number_format($expense->total_price, 2, ',', '.') }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Links --}}
        @if ($project->links->count() > 0)
            <div class="section">
                <div class="section-title">🔗 {{ __('app.public.links') }}</div>
                @foreach ($project->links as $link)
                    <div class="link-item">
                        <div class="link-title">{{ $link->title }}</div>
                        <div class="link-url">{{ $link->url }}</div>
                        @if ($link->description)
                            <div class="link-desc">{{ $link->description }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Archivos --}}
        @if ($project->files->where('type', '!=', 'image')->count() > 0)
            <div class="section">
                <div class="section-title">📁 {{ __('app.public.downloadable_files') }}</div>
                @foreach ($project->files->where('type', '!=', 'image') as $file)
                    <div class="file-item">
                        <span class="file-name">{{ $file->name }}</span>
                        <span class="file-meta"> — {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }} · {{ number_format($file->size / 1024, 0) }} KB</span>
                        @if ($file->description)
                            <span class="file-meta"> · {{ $file->description }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Diario --}}
        @if ($project->diaryEntries->count() > 0)
            <div class="section">
                <div class="section-title">✏️ {{ __('app.public.project_diary') }}</div>
                <div style="margin-top: 14px;">
                    @foreach ($project->diaryEntries as $entry)
                        <div class="timeline-entry">
                            <div class="entry-date">{{ $entry->entry_date->format('d M Y') }}</div>

                            @if ($entry->type)
                                @php
                                    $tc = match($entry->type) { 'progress'=>'entry-type-progress','issue'=>'entry-type-issue','solution'=>'entry-type-solution','milestone'=>'entry-type-milestone',default=>'entry-type-note' };
                                    $tl = match($entry->type) { 'progress'=>__('app.public.entry_types.progress'),'issue'=>__('app.public.entry_types.issue'),'solution'=>__('app.public.entry_types.solution'),'milestone'=>__('app.public.entry_types.milestone'),'note'=>__('app.public.entry_types.note'),default=>ucfirst($entry->type) };
                                @endphp
                                <span class="entry-type {{ $tc }}">{{ $tl }}</span>
                            @endif

                            @if ($entry->title)
                                <div class="entry-title">{{ $entry->title }}</div>
                            @endif

                            <div class="entry-content">{!! $entry->content !!}</div>

                            @if ($entry->images->count() > 0)
                                @php $imgs = $entry->images->take(3); $imgCount = $imgs->count(); @endphp
                                <div style="margin-top:8px;">
                                    <table style="width:{{ $imgCount * 32 }}%; border-collapse:collapse;">
                                        <tr>
                                            @foreach ($imgs as $image)
                                                <td style="width:32%; padding:3px; vertical-align:top;">
                                                    <img src="{{ Storage::disk($image->disk)->path($image->path) }}" style="width:100%; height:auto; border:1px solid #e2e8f0;" alt="{{ $image->caption ?? '' }}">
                                                </td>
                                            @endforeach
                                        </tr>
                                    </table>
                                </div>
                            @endif

                            @if ($entry->time_spent_minutes)
                                <div class="entry-time">⏱ {{ __('app.public.time_dedicated', ['hours' => floor($entry->time_spent_minutes / 60), 'minutes' => $entry->time_spent_minutes % 60]) }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>{{-- /chapter-body --}}

    <div class="chapter-footer">
        {{ $user->name }} · Build Diary · {{ now()->format('Y') }}
    </div>

</div>{{-- /chapter --}}
@endforeach

</body>
</html>
