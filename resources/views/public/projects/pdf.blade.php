<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $project->title }} - Build Diary</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #1e293b;
            padding: 40px;
        }

        .header {
            border-bottom: 2px solid #f59e0b;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            color: #f59e0b;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 28px;
            color: #0f172a;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .meta-item {
            background: #f1f5f9;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 11px;
            color: #475569;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-right: 10px;
        }

        .tags {
            margin-top: 10px;
        }

        .tag {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .dates {
            margin-top: 15px;
            font-size: 11px;
            color: #64748b;
        }

        .dates span {
            margin-right: 20px;
        }

        .section {
            margin-top: 30px;
        }

        .section-title {
            font-size: 18px;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .description {
            line-height: 1.8;
        }

        .description h2 {
            font-size: 16px;
            color: #1e293b;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .description h3 {
            font-size: 14px;
            color: #334155;
            margin-top: 15px;
            margin-bottom: 8px;
        }

        .description ul,
        .description ol {
            margin-left: 20px;
            margin-bottom: 10px;
        }

        .description li {
            margin-bottom: 5px;
        }

        .description p {
            margin-bottom: 10px;
        }

        .description strong {
            font-weight: 600;
        }

        .timeline {
            margin-top: 20px;
        }

        .timeline-entry {
            border-left: 3px solid #f59e0b;
            padding-left: 20px;
            padding-bottom: 25px;
            margin-left: 10px;
            position: relative;
        }

        .timeline-entry:last-child {
            border-left-color: transparent;
            padding-bottom: 0;
        }

        .timeline-entry::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 0;
            width: 12px;
            height: 12px;
            background: #f59e0b;
            border-radius: 50%;
        }

        .entry-date {
            font-size: 11px;
            color: #f59e0b;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .entry-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .entry-type {
            display: inline-block;
            font-size: 9px;
            padding: 2px 8px;
            border-radius: 10px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .entry-type-progress {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .entry-type-issue {
            background: #fee2e2;
            color: #dc2626;
        }

        .entry-type-solution {
            background: #dcfce7;
            color: #16a34a;
        }

        .entry-type-milestone {
            background: #f3e8ff;
            color: #9333ea;
        }

        .entry-type-note {
            background: #f1f5f9;
            color: #475569;
        }

        .entry-content {
            font-size: 11px;
            line-height: 1.7;
            color: #334155;
        }

        .entry-time {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 8px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #94a3b8;
        }

        .footer-content {
            display: table;
            width: 100%;
        }

        .footer-text {
            display: table-cell;
            vertical-align: middle;
            text-align: left;
            width: 70%;
        }

        .footer-qr {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 30%;
        }

        .footer-qr img {
            width: 80px;
            height: 80px;
        }

        .qr-label {
            font-size: 9px;
            color: #64748b;
            margin-top: 5px;
        }

        .page-break {
            page-break-after: always;
        }

        .gallery {
            margin-top: 20px;
        }

        .gallery-grid {
            width: 100%;
        }

        .gallery-item {
            display: inline-block;
            width: 48%;
            margin-right: 2%;
            margin-bottom: 15px;
            vertical-align: top;
        }

        .gallery-item:nth-child(2n) {
            margin-right: 0;
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .gallery-caption {
            font-size: 10px;
            color: #64748b;
            margin-top: 5px;
            text-align: center;
        }

        /* Checklist styles */
        .checklist {
            margin-top: 15px;
        }

        .checklist-progress {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }

        .progress-bar {
            background: #e2e8f0;
            border-radius: 4px;
            height: 8px;
            margin-top: 8px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 4px;
        }

        .progress-bar-fill-complete {
            background: #22c55e;
        }

        .progress-bar-fill-partial {
            background: #f59e0b;
        }

        .task-item {
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11px;
        }

        .task-item:last-child {
            border-bottom: none;
        }

        .task-completed {
            text-decoration: line-through;
            color: #94a3b8;
        }

        .task-icon {
            display: inline-block;
            width: 14px;
            margin-right: 8px;
        }

        /* Budget styles */
        .budget-summary {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .budget-card {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            text-align: center;
            background: #f8fafc;
            border-radius: 8px;
        }

        .budget-card-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .budget-card-value {
            font-size: 16px;
            font-weight: bold;
        }

        .budget-total {
            color: #1e293b;
        }

        .budget-spent {
            color: #22c55e;
        }

        .budget-pending {
            color: #f59e0b;
        }

        .expense-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10px;
        }

        .expense-table th {
            background: #f1f5f9;
            padding: 8px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }

        .expense-table td {
            padding: 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .expense-table tr:last-child td {
            border-bottom: none;
        }

        .expense-purchased {
            color: #94a3b8;
        }

        .expense-category {
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
        }

        .category-material {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .category-tool {
            background: #ffedd5;
            color: #c2410c;
        }

        .category-consumable {
            background: #f1f5f9;
            color: #475569;
        }

        .category-service {
            background: #dcfce7;
            color: #16a34a;
        }

        .category-other {
            background: #f5f5f4;
            color: #78716c;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">📔 Build Diary</div>

        <h1>{{ $project->title }}</h1>

        @if ($project->status)
            <span class="status-badge" style="background-color: {{ $project->status->color }}20; color: {{ $project->status->color }};">
                {{ $project->status->name }}
            </span>
        @endif

        @if ($project->category)
            <span class="meta-item" style="background-color: {{ $project->category->color ?? '#64748b' }}20; color: {{ $project->category->color ?? '#64748b' }};">
                {{ $project->category->name }}
            </span>
        @endif

        @if ($project->priority && $project->priority > 0)
            @php
                $priorityColor = match ($project->priority) {
                    1 => '#22c55e',
                    2 => '#f59e0b',
                    3 => '#ef4444',
                    default => '#64748b',
                };
                $priorityLabel = match ($project->priority) {
                    1 => 'Prioridad baja',
                    2 => 'Prioridad media',
                    3 => 'Prioridad alta',
                    default => '',
                };
            @endphp
            <span class="meta-item" style="background-color: {{ $priorityColor }}20; color: {{ $priorityColor }};">
                {{ $priorityLabel }}
            </span>
        @endif

        @if ($project->tags->count() > 0)
            <div class="tags">
                @foreach ($project->tags as $tag)
                    <span class="tag" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }};">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <div class="dates">
            @if ($project->started_at)
                <span>📅 Iniciado: {{ $project->started_at->format('d/m/Y') }}</span>
            @endif
            @if ($project->due_date)
                <span>⏰ Fecha límite: {{ $project->due_date->format('d/m/Y') }}</span>
            @endif
            @if ($project->completed_at)
                <span>✅ Completado: {{ $project->completed_at->format('d/m/Y') }}</span>
            @endif
        </div>
    </div>

    @if ($project->files->where('type', 'image')->count() > 0)
        <div class="section">
            <h2 class="section-title">📷 Galería de imágenes</h2>
            <div class="gallery">
                <div class="gallery-grid">
                    @foreach ($project->files->where('type', 'image') as $file)
                        <div class="gallery-item">
                            <img src="{{ public_path('storage/' . $file->path) }}" alt="{{ $file->description ?? $file->original_name }}">
                            @if ($file->description)
                                <div class="gallery-caption">{{ $file->description }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if ($project->description)
        <div class="section">
            <h2 class="section-title">Descripción</h2>
            <div class="description">
                {!! Str::markdown($project->description, ['renderer' => ['soft_break' => "<br>\n"]]) !!}
            </div>
        </div>
    @endif

    @if ($project->relationLoaded('tasks') && $project->tasks->count() > 0)
        <div class="section">
            <h2 class="section-title">📋 Checklist</h2>
            @php
                $completedTasks = $project->tasks->where('is_completed', true)->count();
                $totalTasks = $project->tasks->count();
                $taskProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
            @endphp
            <div class="checklist-progress">
                <span style="font-weight: 600;">{{ $completedTasks }} / {{ $totalTasks }} tareas completadas</span>
                <span style="float: right; color: {{ $taskProgress === 100 ? '#22c55e' : '#f59e0b' }}; font-weight: 600;">{{ $taskProgress }}%</span>
                <div class="progress-bar">
                    <div class="progress-bar-fill {{ $taskProgress === 100 ? 'progress-bar-fill-complete' : 'progress-bar-fill-partial' }}" style="width: {{ $taskProgress }}%;"></div>
                </div>
            </div>
            <div class="checklist">
                @foreach ($project->tasks as $task)
                    <div class="task-item {{ $task->is_completed ? 'task-completed' : '' }}">
                        <span class="task-icon">{{ $task->is_completed ? '✓' : '○' }}</span>
                        {{ $task->title }}
                        @if ($task->description)
                            <span style="color: #94a3b8; font-size: 10px;"> — {{ Str::limit($task->description, 50) }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($project->relationLoaded('expenses') && $project->expenses->count() > 0)
        <div class="section">
            <h2 class="section-title">💰 Presupuesto</h2>

            <div class="budget-summary">
                <div class="budget-card" style="margin-right: 10px;">
                    <div class="budget-card-label">Total</div>
                    <div class="budget-card-value budget-total">{{ number_format($project->total_budget, 2, ',', '.') }} €</div>
                </div>
                <div class="budget-card" style="margin-right: 10px;">
                    <div class="budget-card-label">Gastado</div>
                    <div class="budget-card-value budget-spent">{{ number_format($project->spent_budget, 2, ',', '.') }} €</div>
                </div>
                <div class="budget-card">
                    <div class="budget-card-label">Pendiente</div>
                    <div class="budget-card-value budget-pending">{{ number_format($project->pending_budget, 2, ',', '.') }} €</div>
                </div>
            </div>

            @php
                $budgetProgress = $project->budget_progress;
            @endphp
            <div class="checklist-progress" style="margin-bottom: 20px;">
                <span style="font-weight: 600;">Progreso del gasto</span>
                <span style="float: right; color: {{ $budgetProgress === 100 ? '#22c55e' : '#f59e0b' }}; font-weight: 600;">{{ $budgetProgress }}%</span>
                <div class="progress-bar">
                    <div class="progress-bar-fill {{ $budgetProgress === 100 ? 'progress-bar-fill-complete' : 'progress-bar-fill-partial' }}" style="width: {{ $budgetProgress }}%;"></div>
                </div>
            </div>

            <table class="expense-table">
                <thead>
                    <tr>
                        <th style="width: 5%;"></th>
                        <th style="width: 35%;">Material</th>
                        <th style="width: 20%;">Categoría</th>
                        <th style="width: 20%;">Cantidad</th>
                        <th class="text-right" style="width: 20%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($project->expenses as $expense)
                        <tr class="{{ $expense->is_purchased ? 'expense-purchased' : '' }}">
                            <td>{{ $expense->is_purchased ? '✓' : '○' }}</td>
                            <td>
                                {{ $expense->name }}
                                @if ($expense->supplier)
                                    <span style="color: #94a3b8; font-size: 9px;"> — {{ $expense->supplier }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $categoryClass = match ($expense->category) {
                                        'material' => 'category-material',
                                        'tool' => 'category-tool',
                                        'consumable' => 'category-consumable',
                                        'service' => 'category-service',
                                        default => 'category-other',
                                    };
                                @endphp
                                <span class="expense-category {{ $categoryClass }}">{{ $expense->category_label }}</span>
                            </td>
                            <td>{{ $expense->quantity }}{{ $expense->unit ? ' ' . $expense->unit : '' }}</td>
                            <td class="text-right font-bold">{{ number_format($expense->total_price, 2, ',', '.') }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($project->links->count() > 0)
        <div class="section">
            <h2 class="section-title">🔗 Enlaces</h2>
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach ($project->links as $link)
                    <li style="margin-bottom: 12px; padding-left: 20px; position: relative;">
                        <span style="position: absolute; left: 0; color: #f59e0b;">→</span>
                        <a href="{{ $link->url }}" style="color: #d97706; text-decoration: none; font-weight: 500;">
                            {{ $link->title }}
                        </a>
                        <br>
                        <span style="color: #64748b; font-size: 11px;">{{ $link->url }}</span>
                        @if ($link->description)
                            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 12px;">{{ $link->description }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($project->files->where('type', '!=', 'image')->count() > 0)
        <div class="section">
            <h2 class="section-title">📁 Archivos descargables</h2>
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach ($project->files->where('type', '!=', 'image') as $file)
                    <li style="margin-bottom: 12px; padding-left: 20px; position: relative;">
                        <span style="position: absolute; left: 0; color: #f59e0b;">
                            @if ($file->type === 'stl')
                                🗃️
                            @elseif ($file->type === 'pdf')
                                📄
                            @else
                                📎
                            @endif
                        </span>
                        <span style="font-weight: 500; color: #1e293b;">{{ $file->name }}</span>
                        <br>
                        <span style="color: #64748b; font-size: 11px;">
                            {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }} · {{ number_format($file->size / 1024, 0) }} KB
                            @if ($file->description)
                                · {{ $file->description }}
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($project->diaryEntries->count() > 0)
        <div class="section">
            <h2 class="section-title">Diario del proyecto</h2>
            <div class="timeline">
                @foreach ($project->diaryEntries as $entry)
                    <div class="timeline-entry">
                        <div class="entry-date">{{ $entry->entry_date->format('d M Y') }}</div>

                        @if ($entry->type)
                            @php
                                $typeClass = match ($entry->type) {
                                    'progress' => 'entry-type-progress',
                                    'issue' => 'entry-type-issue',
                                    'solution' => 'entry-type-solution',
                                    'milestone' => 'entry-type-milestone',
                                    default => 'entry-type-note',
                                };
                                $typeLabel = match ($entry->type) {
                                    'progress' => 'Progreso',
                                    'issue' => 'Problema',
                                    'solution' => 'Solución',
                                    'milestone' => 'Hito',
                                    'note' => 'Nota',
                                    default => ucfirst($entry->type),
                                };
                            @endphp
                            <span class="entry-type {{ $typeClass }}">{{ $typeLabel }}</span>
                        @endif

                        @if ($entry->title)
                            <div class="entry-title">{{ $entry->title }}</div>
                        @endif

                        <div class="entry-content">
                            {!! $entry->content !!}
                        </div>

                        @if ($entry->time_spent_minutes)
                            <div class="entry-time">
                                ⏱️ {{ floor($entry->time_spent_minutes / 60) }}h {{ $entry->time_spent_minutes % 60 }}m dedicados
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="footer">
        <div class="footer-content">
            <div class="footer-text">
                <strong>📔 Build Diary</strong><br>
                Generado el {{ now()->format('d/m/Y H:i') }}<br>
                <span style="color: #f59e0b;">{{ route('public.project.show', $project->slug) }}</span>
            </div>
            <div class="footer-qr">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('public.project.show', $project->slug)) }}" alt="QR Code">
                <div class="qr-label">Escanea para ver online</div>
            </div>
        </div>
    </div>
</body>

</html>
