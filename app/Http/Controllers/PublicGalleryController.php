<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\View\View;

final class PublicGalleryController extends Controller
{
    public function show(User $user): View
    {
        $projects = Project::query()
            ->where('user_id', $user->id)
            ->where('is_public', true)
            ->with(['files' => function ($query): void {
                $query->where('type', 'image')->orderBy('order');
            }, 'status', 'tags', 'category'])
            ->withCount(['files as images_count' => function ($query): void {
                $query->where('type', 'image');
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('public.gallery', compact('user', 'projects'));
    }
}
