<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class TagController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View|JsonResponse
    {
        $this->authorize('viewAny', Tag::class);

        $tags = Tag::forUser($request->user()->id)
            ->when($request->filled('search'), fn ($q) => $q->byName((string) $request->string('search')))
            ->withCount(['projects', 'people'])
            ->orderBy('name')
            ->get();

        if ($request->wantsJson()) {
            return response()->json($tags);
        }

        return view('tags.index', compact('tags'));
    }

    public function store(StoreTagRequest $request): RedirectResponse|JsonResponse
    {
        $this->authorize('create', Tag::class);

        $tag = Tag::create([
            'user_id' => $request->user()->id,
            'name' => $request->validated('name'),
            'slug' => Str::slug($request->validated('name')),
            'color' => $request->validated('color', '#3B82F6'),
        ]);

        if ($request->wantsJson()) {
            return response()->json($tag, 201);
        }

        return back()->with('success', 'Etiqueta creada correctamente.');
    }

    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $tag);

        $tag->update([
            'name' => $request->validated('name'),
            'slug' => Str::slug($request->validated('name')),
            'color' => $request->validated('color', $tag->color),
        ]);

        if ($request->wantsJson()) {
            return response()->json($tag->fresh());
        }

        return back()->with('success', 'Etiqueta actualizada correctamente.');
    }

    public function destroy(Request $request, Tag $tag): RedirectResponse|JsonResponse
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return back()->with('success', 'Etiqueta eliminada correctamente.');
    }
}
