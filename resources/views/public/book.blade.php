<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $user->name }} — {{ __('app.public.book_title') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /*
         * @page handles ONLY top/bottom margins — DomPDF applies these reliably.
         * Horizontal margins come from .content-wrapper padding (see below).
         *
         * A4 = 210 × 297 mm
         * Vertical content area: 297 - 22 - 28 = 247 mm
         * Horizontal content area: 210 - 26 - 20 = 164 mm  (via .content-wrapper)
         */
        @page { margin: 22mm 0 28mm 0; }

        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Serif', serif;
            font-size: 10.5px;
            line-height: 1.75;
            color: #1a1207;
            background: #fff;
        }

        /*
         * All non-cover content lives here.
         * left: 26mm, right: 20mm — simulates binding margin vs trim margin.
         */
        .content-wrapper {
            padding: 0 20mm 0 26mm;
        }

        /* =====================================================
           RUNNING HEADER
           position:fixed is relative to the @page content box.
           @page left = 0, right = 0, so left:0/right:0 = physical page edges.
           top: -17mm  →  22mm(top margin) - 17mm = 5mm from physical top.
           ===================================================== */
        #running-header {
            position: fixed;
            top: -17mm;
            left: 0;
            right: 0;
            height: 10mm;
            padding: 4mm 20mm 0 26mm;
            border-bottom: 0.5px solid #c4a882;
            background: #fff;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7.5px;
            color: #9c8060;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        #running-header .rh-left  { float: left; }
        #running-header .rh-right { float: right; }
        #running-header .rh-clear { clear: both; }

        /* =====================================================
           RUNNING FOOTER
           bottom: -23mm → 28mm(bottom margin) - 23mm = 5mm from physical bottom.
           ===================================================== */
        #running-footer {
            position: fixed;
            bottom: -23mm;
            left: 0;
            right: 0;
            height: 10mm;
            padding: 3mm 20mm 0 26mm;
            border-top: 0.5px solid #c4a882;
            background: #fff;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7.5px;
            color: #9c8060;
            text-align: center;
            letter-spacing: 0.5px;
        }

        /* =====================================================
           COVER
           Full-bleed (body has no padding).
           Height = vertical content area: 247mm.
           ===================================================== */
        .cover-table {
            width: 100%;
            height: 247mm;
            background: #1c1409;
            border-collapse: collapse;
            page-break-after: always;
        }
        .cover-outer-td {
            padding: 8mm;
            vertical-align: top;
            border: 1.5px solid #6b4f2e;
        }
        .cover-inner-table {
            width: 100%;
            height: 225mm;
            border-collapse: collapse;
            border: 0.5px solid #40301a;
        }
        .cover-inner-td {
            text-align: center;
            vertical-align: middle;
            padding: 10mm 18mm;
        }
        .cover-publisher {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7px;
            color: #c4a882;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 6mm;
        }
        .cover-rule {
            border: none;
            border-top: 0.5px solid #6b4f2e;
            width: 55%;
            margin: 0 auto 6mm;
        }
        .cover-rule-narrow {
            border: none;
            border-top: 0.5px solid #6b4f2e;
            width: 32%;
            margin: 4mm auto;
        }
        .cover-title {
            font-size: 30px;
            color: #f5e6c8;
            font-weight: bold;
            line-height: 1.2;
            margin-bottom: 3mm;
            letter-spacing: 1px;
        }
        .cover-ornament {
            font-family: 'DejaVu Sans', sans-serif;
            color: #c4a882;
            font-size: 13px;
            margin: 3mm 0;
            letter-spacing: 8px;
        }
        .cover-author {
            font-size: 13px;
            color: #c4a882;
            letter-spacing: 3px;
            font-style: italic;
            margin: 3mm 0;
        }
        .cover-meta {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            color: #6b4f2e;
            line-height: 2.2;
            letter-spacing: 0.5px;
            margin-top: 4mm;
        }
        .cover-url {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7px;
            color: #40301a;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 7mm;
        }

        /* =====================================================
           TABLE OF CONTENTS
           ===================================================== */
        .toc-page { page-break-after: always; padding-top: 4mm; }

        .toc-header { text-align: center; margin-bottom: 5mm; }
        .toc-label {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7px;
            color: #9c8060;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 2mm;
        }
        .toc-title {
            font-size: 22px;
            color: #1c1409;
            font-weight: bold;
            margin-bottom: 1mm;
        }
        .toc-ornament {
            font-family: 'DejaVu Sans', sans-serif;
            color: #c4a882;
            font-size: 11px;
            letter-spacing: 6px;
            margin-bottom: 4mm;
        }
        .toc-rule {
            border: none;
            border-top: 1px solid #d4b896;
            margin-bottom: 4mm;
        }
        .toc-table { width: 100%; border-collapse: collapse; }
        .toc-table tr { border-bottom: 1px dotted #e8d9c4; }
        .toc-table td { padding: 5px 4px; vertical-align: middle; }
        .toc-num {
            width: 28px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            color: #9c8060;
        }
        .toc-entry-title { font-size: 10.5px; color: #2c1e0a; font-weight: bold; }
        .toc-entry-sub {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            color: #9c8060;
            font-style: italic;
        }
        .toc-status { width: 76px; text-align: right; }
        .toc-badge {
            font-family: 'DejaVu Sans', sans-serif;
            display: inline-block;
            padding: 1px 7px;
            border-radius: 8px;
            font-size: 7.5px;
            font-weight: 600;
        }

        /* =====================================================
           CHAPTER
           ===================================================== */
        .chapter { page-break-before: always; }

        .chapter-opening {
            text-align: center;
            padding: 8mm 0 7mm;
            border-bottom: 1px solid #d4b896;
            margin-bottom: 7mm;
        }
        .chapter-label {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7px;
            color: #9c8060;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-bottom: 2mm;
        }
        .chapter-roman {
            font-size: 26px;
            color: #c4a882;
            font-weight: bold;
            line-height: 1;
            margin-bottom: 3mm;
        }
        .chapter-rule-short {
            border: none;
            border-top: 1px solid #c4a882;
            width: 36px;
            margin: 0 auto 3mm;
        }
        .chapter-title {
            font-size: 18px;
            color: #1c1409;
            font-weight: bold;
            line-height: 1.25;
            margin-bottom: 2mm;
        }
        .chapter-ornament {
            font-family: 'DejaVu Sans', sans-serif;
            color: #c4a882;
            font-size: 10px;
            letter-spacing: 5px;
            margin: 2mm 0;
        }
        .chapter-badges { text-align: center; margin-top: 2mm; }
        .badge {
            display: inline-block;
            font-family: 'DejaVu Sans', sans-serif;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 600;
            margin: 1px 2px;
        }
        .chapter-dates {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8.5px;
            color: #9c8060;
            text-align: center;
            margin-top: 2mm;
            font-style: italic;
        }

        /* =====================================================
           SECTIONS
           ===================================================== */
        .section { margin-bottom: 7mm; }

        .section-heading {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7px;
            color: #9c8060;
            letter-spacing: 4px;
            text-transform: uppercase;
            text-align: center;
            padding-top: 4mm;
            margin-bottom: 2mm;
        }
        .section-rule {
            border: none;
            border-top: 0.5px solid #d4b896;
            margin-bottom: 4mm;
        }

        /* =====================================================
           DESCRIPTION
           ===================================================== */
        .description { line-height: 1.78; color: #2c1e0a; text-align: justify; }
        .description p { margin-bottom: 5px; text-indent: 1em; }
        .description p:first-child { text-indent: 0; }
        .description h2 { font-size: 13px; color: #1c1409; margin: 10px 0 5px; }
        .description h3 { font-size: 11px; color: #2c1e0a; margin: 8px 0 4px; }
        .description ul, .description ol { margin-left: 16px; margin-bottom: 6px; }
        .description li { margin-bottom: 2px; }
        .description strong { font-weight: bold; }
        .description em { font-style: italic; }
        .description blockquote {
            border-left: 2px solid #c4a882;
            padding-left: 10px;
            color: #7a6040;
            font-style: italic;
            margin: 6px 0 6px 4px;
        }
        .description code {
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 9px;
            background: #f5ede0;
            padding: 1px 3px;
        }

        /* =====================================================
           GALLERY
           ===================================================== */
        .gallery-table { width: 100%; border-collapse: collapse; }
        .gallery-td { width: 50%; padding: 3px; vertical-align: top; }
        .gallery-td img { width: 100%; height: auto; border: 0.5px solid #d4b896; }
        .gallery-caption {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7.5px;
            color: #9c8060;
            text-align: center;
            margin-top: 2px;
            font-style: italic;
        }

        /* =====================================================
           DEDICATED TO
           ===================================================== */
        .dedicated {
            text-align: center;
            padding: 4mm 8mm;
            margin-bottom: 6mm;
            border-top: 0.5px solid #d4b896;
            border-bottom: 0.5px solid #d4b896;
        }
        .dedicated-label {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7.5px;
            color: #9c8060;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 1mm;
        }
        .dedicated-name { font-size: 13px; color: #2c1e0a; font-style: italic; }
        .dedicated-reason {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            color: #9c8060;
            margin-top: 1mm;
        }

        /* =====================================================
           CHECKLIST
           ===================================================== */
        .checklist-summary {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8.5px;
            color: #7a6040;
            margin-bottom: 2mm;
            text-align: center;
            font-style: italic;
        }
        .progress-wrap { background: #ece0d0; height: 5px; margin-bottom: 3mm; }
        .progress-fill  { height: 100%; }

        .task-table { width: 100%; border-collapse: collapse; }
        .task-table tr { border-bottom: 0.5px solid #ece0d0; }
        .task-table tr:last-child { border-bottom: none; }
        .task-check {
            width: 14px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #9c8060;
            vertical-align: top;
            padding: 4px 4px 4px 0;
        }
        .task-text {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9.5px;
            color: #2c1e0a;
            padding: 4px 0;
            vertical-align: top;
        }
        .task-done-text { text-decoration: line-through; color: #b0a090; }

        /* =====================================================
           BUDGET
           ===================================================== */
        .budget-summary-table { width: 100%; border-collapse: collapse; margin-bottom: 3mm; }
        .budget-cell {
            width: 33.33%;
            text-align: center;
            padding: 4mm 2mm;
            border: 0.5px solid #d4b896;
        }
        .budget-label {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7px;
            color: #9c8060;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .budget-value { font-size: 13px; font-weight: bold; color: #2c1e0a; }

        .expense-table { width: 100%; border-collapse: collapse; font-family: 'DejaVu Sans', sans-serif; font-size: 8.5px; }
        .expense-table th {
            padding: 4px 6px;
            text-align: left;
            font-weight: 600;
            color: #7a6040;
            font-size: 7.5px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-bottom: 1px solid #c4a882;
        }
        .expense-table td { padding: 4px 6px; border-bottom: 0.5px solid #ece0d0; color: #2c1e0a; }
        .expense-table tr:last-child td { border-bottom: none; }
        .expense-done td { color: #b0a090; }
        .text-right { text-align: right; }
        .font-bold  { font-weight: bold; }

        .expense-cat { font-size: 7px; padding: 1px 5px; border-radius: 3px; }
        .cat-material   { background: #e8f0fe; color: #3b5bdb; }
        .cat-tool       { background: #fff3e0; color: #b45309; }
        .cat-consumable { background: #f5f0e8; color: #7a6040; }
        .cat-service    { background: #e6f4ea; color: #2d6a4f; }
        .cat-other      { background: #f0ece6; color: #7a6040; }

        /* =====================================================
           LINKS
           ===================================================== */
        .link-item { margin-bottom: 4mm; padding-left: 10px; border-left: 1px solid #d4b896; }
        .link-title { font-size: 10px; font-weight: bold; color: #2c1e0a; }
        .link-url { font-family: 'DejaVu Sans Mono', monospace; font-size: 7.5px; color: #9c8060; word-break: break-all; }
        .link-desc { font-family: 'DejaVu Sans', sans-serif; font-size: 8.5px; color: #7a6040; font-style: italic; margin-top: 1px; }

        /* =====================================================
           FILES
           ===================================================== */
        .file-table { width: 100%; border-collapse: collapse; }
        .file-table tr { border-bottom: 0.5px solid #ece0d0; }
        .file-table tr:last-child { border-bottom: none; }
        .file-bullet-td {
            width: 10px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #c4a882;
            vertical-align: top;
            padding: 3px 4px 3px 0;
        }
        .file-name-td {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #2c1e0a;
            padding: 3px 0;
            vertical-align: top;
        }
        .file-ext {
            font-size: 7px;
            background: #f5ede0;
            color: #9c8060;
            padding: 1px 4px;
            border-radius: 2px;
            margin-left: 4px;
        }

        /* =====================================================
           DIARY
           ===================================================== */
        .diary-entry {
            margin-bottom: 6mm;
            padding-bottom: 6mm;
            border-bottom: 0.5px solid #ece0d0;
        }
        .diary-entry:last-child { border-bottom: none; margin-bottom: 0; }

        .diary-table { width: 100%; border-collapse: collapse; }
        .diary-date-td { width: 22mm; vertical-align: top; padding-right: 4mm; }
        .diary-content-td { vertical-align: top; }

        .entry-day-num {
            font-size: 18px;
            font-weight: bold;
            color: #c4a882;
            line-height: 1;
        }
        .entry-month-year {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7px;
            color: #9c8060;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .entry-type {
            font-family: 'DejaVu Sans', sans-serif;
            display: inline-block;
            font-size: 7px;
            padding: 1px 6px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 2px;
        }
        .etype-progress  { background: #dbeafe; color: #1e40af; }
        .etype-issue     { background: #fee2e2; color: #b91c1c; }
        .etype-solution  { background: #dcfce7; color: #166534; }
        .etype-milestone { background: #f3e8ff; color: #6d28d9; }
        .etype-note      { background: #f5f0e8; color: #7a6040; }

        .entry-title { font-size: 11px; font-weight: bold; color: #1c1409; margin-bottom: 2px; }
        .entry-content {
            font-size: 9.5px;
            line-height: 1.65;
            color: #2c1e0a;
            text-align: justify;
        }
        .entry-content p { margin-bottom: 4px; }
        .entry-content ul, .entry-content ol { margin-left: 12px; }
        .entry-time {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7.5px;
            color: #b0a090;
            margin-top: 3px;
            font-style: italic;
        }

        /* =====================================================
           COLOPHON
           ===================================================== */
        .chapter-colophon {
            text-align: center;
            margin-top: 8mm;
            padding-top: 4mm;
            border-top: 0.5px solid #d4b896;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7px;
            color: #c4a882;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

{{-- ===== RUNNING HEADER / FOOTER ===== --}}
<div id="running-header">
    <span class="rh-left">{{ $user->name }}</span>
    <span class="rh-right">{{ __('app.public.book_title') }}</span>
    <div class="rh-clear"></div>
</div>
<div id="running-footer">
    Build Diary &mdash; {{ now()->format('Y') }}
</div>


{{-- ═══════════════════════════════════════════════════
     PORTADA — full-bleed (body sin padding)
     ═══════════════════════════════════════════════════ --}}
<table class="cover-table">
    <tr>
        <td class="cover-outer-td">
            <table class="cover-inner-table">
                <tr>
                    <td class="cover-inner-td">
                        <div class="cover-publisher">Build Diary</div>
                        <hr class="cover-rule">
                        <div class="cover-title">{{ __('app.public.book_cover_title') }}</div>
                        <div class="cover-ornament">&#10022; &#10022; &#10022;</div>
                        <div class="cover-author">{{ $user->name }}</div>
                        <hr class="cover-rule-narrow">
                        <div class="cover-meta">
                            {{ $projects->count() }} {{ strtolower(trans_choice('app.public.book_projects_count', $projects->count(), ['count' => $projects->count()])) }}<br>
                            {{ __('app.public.book_generated_on', ['date' => now()->format('d \d\e F \d\e Y')]) }}
                            @php $totalEntries = $projects->sum(fn ($p) => $p->diaryEntries->count()); @endphp
                            @if ($totalEntries > 0)
                                <br>{{ $totalEntries }} {{ __('app.public.book_diary_entries') }}
                            @endif
                        </div>
                        <div class="cover-url">{{ config('app.url') }}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


{{-- ═══════════════════════════════════════════════════
     Todo el resto tiene márgenes horizontales
     ═══════════════════════════════════════════════════ --}}
<div class="content-wrapper">

    {{-- ===== ÍNDICE ===== --}}
    <div class="toc-page">
        <div class="toc-header">
            <div class="toc-label">{{ __('app.public.book_title') }}</div>
            <div class="toc-title">{{ __('app.public.book_toc') }}</div>
            <div class="toc-ornament">&#10022; &#10022; &#10022;</div>
        </div>
        <hr class="toc-rule">
        <table class="toc-table">
            @foreach ($projects as $i => $project)
                @php
                    $romans = ['I','II','III','IV','V','VI','VII','VIII','IX','X',
                               'XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX',
                               'XXI','XXII','XXIII','XXIV','XXV'];
                    $roman = $romans[$i] ?? ($i + 1);
                @endphp
                <tr>
                    <td class="toc-num">{{ $roman }}</td>
                    <td>
                        <div class="toc-entry-title">{{ $project->title }}</div>
                        <div class="toc-entry-sub">
                            @if ($project->category){{ $project->category->name }}@if ($project->started_at || $project->diaryEntries->count() > 0), @endif @endif
                            @if ($project->started_at){{ $project->started_at->format('Y') }} @endif
                            @if ($project->diaryEntries->count() > 0)
                                &middot; {{ $project->diaryEntries->count() }} {{ __('app.public.book_diary_entries') }}
                            @endif
                        </div>
                    </td>
                    @if ($project->status)
                        <td class="toc-status">
                            <span class="toc-badge" style="background-color:{{ $project->status->color }}22; color:{{ $project->status->color }};">
                                {{ $project->status->name }}
                            </span>
                        </td>
                    @else
                        <td class="toc-status"></td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>


    {{-- ===== CAPÍTULOS ===== --}}
    @foreach ($projects as $i => $project)
    @php
        $romans = ['I','II','III','IV','V','VI','VII','VIII','IX','X',
                   'XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX',
                   'XXI','XXII','XXIII','XXIV','XXV'];
        $roman = $romans[$i] ?? ($i + 1);
    @endphp
    <div class="chapter">

        <div class="chapter-opening">
            <div class="chapter-label">{{ __('app.public.book_chapter', ['number' => '']) }}</div>
            <div class="chapter-roman">{{ $roman }}</div>
            <hr class="chapter-rule-short">
            <div class="chapter-title">{{ $project->title }}</div>
            <div class="chapter-ornament">&#10022; &#10022; &#10022;</div>

            <div class="chapter-badges">
                @if ($project->status)
                    <span class="badge" style="background-color:{{ $project->status->color }}22; color:{{ $project->status->color }};">{{ $project->status->name }}</span>
                @endif
                @if ($project->category)
                    <span class="badge" style="background-color:{{ $project->category->color ?? '#c4a882' }}22; color:{{ $project->category->color ?? '#9c8060' }};">{{ $project->category->name }}</span>
                @endif
                @if ($project->priority && $project->priority > 0)
                    @php
                        $pc = match($project->priority) { 1 => '#22c55e', 2 => '#d97706', 3 => '#dc2626', default => '#9c8060' };
                        $pl = match($project->priority) { 1 => __('app.public.priority_low'), 2 => __('app.public.priority_medium'), 3 => __('app.public.priority_high'), default => '' };
                    @endphp
                    <span class="badge" style="background-color:{{ $pc }}22; color:{{ $pc }};">{{ $pl }}</span>
                @endif
                @foreach ($project->tags as $tag)
                    <span class="badge" style="background-color:{{ $tag->color }}22; color:{{ $tag->color }};">{{ $tag->name }}</span>
                @endforeach
            </div>

            @if ($project->started_at || $project->due_date || $project->completed_at)
                <div class="chapter-dates">
                    @if ($project->started_at){{ __('app.public.started') }}: {{ $project->started_at->format('d/m/Y') }}@endif
                    @if ($project->due_date) &mdash; {{ __('app.public.due_date') }}: {{ $project->due_date->format('d/m/Y') }}@endif
                    @if ($project->completed_at) &mdash; {{ __('app.public.completed') }}: {{ $project->completed_at->format('d/m/Y') }}@endif
                </div>
            @endif
        </div>

        {{-- Dedicatoria --}}
        @if ($project->person)
            <div class="dedicated">
                <div class="dedicated-label">{{ __('app.public.dedicated_to') }}</div>
                <div class="dedicated-name">{{ $project->person->name }}</div>
                @if ($project->person_reason)
                    <div class="dedicated-reason">{{ $project->person_reason }}</div>
                @endif
            </div>
        @endif

        {{-- Galería --}}
        @if ($project->files->where('type', 'image')->count() > 0)
            <div class="section">
                <div class="section-heading">{{ __('app.public.image_gallery') }}</div>
                <hr class="section-rule">
                <table class="gallery-table">
                    @foreach ($project->files->where('type', 'image')->take(6)->chunk(2) as $row)
                        <tr>
                            @foreach ($row as $file)
                                <td class="gallery-td">
                                    <img src="{{ Storage::disk($file->disk)->path($file->path) }}" alt="{{ $file->description ?? $file->original_name }}">
                                    @if ($file->description)
                                        <div class="gallery-caption">{{ $file->description }}</div>
                                    @endif
                                </td>
                            @endforeach
                            @if ($row->count() === 1)<td class="gallery-td"></td>@endif
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        {{-- Descripción --}}
        @if ($project->description)
            <div class="section">
                <div class="section-heading">{{ __('app.public.description') }}</div>
                <hr class="section-rule">
                <div class="description">
                    {!! Str::markdown($project->description, ['renderer' => ['soft_break' => "<br>\n"]]) !!}
                </div>
            </div>
        @endif

        {{-- Checklist --}}
        @if ($project->tasks->count() > 0)
            @php
                $done     = $project->tasks->where('is_completed', true)->count();
                $total    = $project->tasks->count();
                $pct      = $total > 0 ? round(($done / $total) * 100) : 0;
                $barColor = $pct === 100 ? '#4a8c5c' : '#c4a882';
            @endphp
            <div class="section">
                <div class="section-heading">{{ __('app.public.checklist') }}</div>
                <hr class="section-rule">
                <div class="checklist-summary">
                    {{ __('app.public.tasks_completed', ['completed' => $done, 'total' => $total]) }}
                    &ensp;&mdash;&ensp; {{ $pct }}%
                </div>
                <div class="progress-wrap">
                    <div class="progress-fill" style="width:{{ $pct }}%; background:{{ $barColor }};"></div>
                </div>
                <table class="task-table">
                    @foreach ($project->tasks as $task)
                        <tr>
                            <td class="task-check">{{ $task->is_completed ? '&#10003;' : '&#9675;' }}</td>
                            <td class="task-text {{ $task->is_completed ? 'task-done-text' : '' }}">
                                {{ $task->title }}
                                @if ($task->description && !$task->is_completed)
                                    <span style="color:#b0a090; font-size:8px; font-style:italic;"> &mdash; {{ Str::limit($task->description, 80) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        {{-- Presupuesto --}}
        @if ($project->expenses->count() > 0)
            <div class="section">
                <div class="section-heading">{{ __('app.public.budget') }}</div>
                <hr class="section-rule">
                <table class="budget-summary-table">
                    <tr>
                        <td class="budget-cell">
                            <div class="budget-label">{{ __('app.public.total') }}</div>
                            <div class="budget-value">{{ number_format($project->total_budget, 2, ',', '.') }} &euro;</div>
                        </td>
                        <td class="budget-cell" style="border-left:none;">
                            <div class="budget-label">{{ __('app.public.spent') }}</div>
                            <div class="budget-value" style="color:#4a8c5c;">{{ number_format($project->spent_budget, 2, ',', '.') }} &euro;</div>
                        </td>
                        <td class="budget-cell" style="border-left:none;">
                            <div class="budget-label">{{ __('app.public.pending') }}</div>
                            <div class="budget-value" style="color:#c4a882;">{{ number_format($project->pending_budget, 2, ',', '.') }} &euro;</div>
                        </td>
                    </tr>
                </table>
                <table class="expense-table">
                    <thead>
                        <tr>
                            <th style="width:4%;"></th>
                            <th style="width:38%;">{{ __('app.public.expense_item') }}</th>
                            <th style="width:20%;">{{ __('app.public.expense_category_header') }}</th>
                            <th style="width:16%;">{{ __('app.public.expense_quantity') }}</th>
                            <th class="text-right" style="width:22%;">{{ __('app.public.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($project->expenses as $expense)
                            @php
                                $cc = match($expense->category) {
                                    'material'   => 'cat-material',
                                    'tool'       => 'cat-tool',
                                    'consumable' => 'cat-consumable',
                                    'service'    => 'cat-service',
                                    default      => 'cat-other',
                                };
                            @endphp
                            <tr class="{{ $expense->is_purchased ? 'expense-done' : '' }}">
                                <td>{{ $expense->is_purchased ? '&#10003;' : '&#9675;' }}</td>
                                <td>
                                    {{ $expense->name }}
                                    @if ($expense->supplier)
                                        <span style="color:#b0a090; font-size:7.5px;"> &mdash; {{ $expense->supplier }}</span>
                                    @endif
                                </td>
                                <td><span class="expense-cat {{ $cc }}">{{ $expense->category_label }}</span></td>
                                <td>{{ $expense->quantity }}{{ $expense->unit ? ' '.$expense->unit : '' }}</td>
                                <td class="text-right font-bold">{{ number_format($expense->total_price, 2, ',', '.') }} &euro;</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Links --}}
        @if ($project->links->count() > 0)
            <div class="section">
                <div class="section-heading">{{ __('app.public.links') }}</div>
                <hr class="section-rule">
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
                <div class="section-heading">{{ __('app.public.downloadable_files') }}</div>
                <hr class="section-rule">
                <table class="file-table">
                    @foreach ($project->files->where('type', '!=', 'image') as $file)
                        <tr>
                            <td class="file-bullet-td">&bull;</td>
                            <td class="file-name-td">
                                {{ $file->name }}
                                <span class="file-ext">{{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}</span>
                                <span style="color:#b0a090; font-size:7.5px;"> &mdash; {{ number_format($file->size / 1024, 0) }} KB</span>
                                @if ($file->description)
                                    <span style="color:#7a6040; font-size:8px; font-style:italic;"> &mdash; {{ $file->description }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        {{-- Diario --}}
        @if ($project->diaryEntries->count() > 0)
            <div class="section">
                <div class="section-heading">{{ __('app.public.project_diary') }}</div>
                <hr class="section-rule">

                @foreach ($project->diaryEntries as $entry)
                    <div class="diary-entry">
                        <table class="diary-table">
                            <tr>
                                <td class="diary-date-td">
                                    <div class="entry-day-num">{{ $entry->entry_date->format('d') }}</div>
                                    <div class="entry-month-year">{{ $entry->entry_date->format('M Y') }}</div>
                                </td>
                                <td class="diary-content-td">
                                    @if ($entry->type)
                                        @php
                                            $tc = match($entry->type) {
                                                'progress'  => 'etype-progress',
                                                'issue'     => 'etype-issue',
                                                'solution'  => 'etype-solution',
                                                'milestone' => 'etype-milestone',
                                                default     => 'etype-note',
                                            };
                                            $tl = match($entry->type) {
                                                'progress'  => __('app.public.entry_types.progress'),
                                                'issue'     => __('app.public.entry_types.issue'),
                                                'solution'  => __('app.public.entry_types.solution'),
                                                'milestone' => __('app.public.entry_types.milestone'),
                                                default     => __('app.public.entry_types.note'),
                                            };
                                        @endphp
                                        <span class="entry-type {{ $tc }}">{{ $tl }}</span>
                                    @endif

                                    @if ($entry->title)
                                        <div class="entry-title">{{ $entry->title }}</div>
                                    @endif

                                    <div class="entry-content">{!! $entry->content !!}</div>

                                    @if ($entry->images->count() > 0)
                                        <table style="width:100%; border-collapse:collapse; margin-top:4px;">
                                            <tr>
                                                @foreach ($entry->images->take(3) as $image)
                                                    <td style="width:32%; padding:2px; vertical-align:top;">
                                                        <img src="{{ Storage::disk($image->disk)->path($image->path) }}"
                                                             style="width:100%; height:auto; border:0.5px solid #d4b896;"
                                                             alt="{{ $image->caption ?? '' }}">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </table>
                                    @endif

                                    @if ($entry->time_spent_minutes)
                                        <div class="entry-time">
                                            {{ __('app.public.time_dedicated', ['hours' => floor($entry->time_spent_minutes / 60), 'minutes' => $entry->time_spent_minutes % 60]) }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="chapter-colophon">
            {{ $user->name }} &nbsp;&mdash;&nbsp; {{ $roman }} &nbsp;&mdash;&nbsp; {{ $project->title }}
        </div>

    </div>{{-- /chapter --}}
    @endforeach

</div>{{-- /content-wrapper --}}

</body>
</html>
