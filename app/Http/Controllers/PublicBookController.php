<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

final class PublicBookController extends Controller
{
    public function pdf(User $user): Response
    {
        $projects = Project::query()
            ->where('user_id', $user->id)
            ->where('is_public', true)
            ->with([
                'status',
                'category',
                'tags',
                'person',
                'files' => fn ($q) => $q->orderBy('order'),
                'tasks' => fn ($q) => $q->orderBy('order'),
                'expenses' => fn ($q) => $q->orderBy('category')->orderBy('name'),
                'links' => fn ($q) => $q->orderBy('order'),
                'diaryEntries' => fn ($q) => $q->latest('entry_date')->with('images'),
            ])
            ->orderBy('updated_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('public.book', compact('user', 'projects'))
            ->setPaper('a4')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isPhpEnabled', true);

        $filename = 'build-diary-'.$user->id.'.pdf';

        return $pdf->download($filename);
    }
}
