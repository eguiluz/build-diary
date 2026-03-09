<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectLink;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ProjectLinkController extends Controller
{
    use AuthorizesRequests;

    public function index(Project $project): JsonResponse
    {
        $this->authorize('manageLinks', $project);

        return response()->json($project->links);
    }

    public function store(Request $request, Project $project): JsonResponse|RedirectResponse
    {
        $this->authorize('manageLinks', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2000'],
            'type' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $link = $project->links()->create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'type' => $validated['type'] ?? 'reference',
            'description' => $validated['description'] ?? null,
            'order' => ($project->links()->max('order') ?? 0) + 1,
        ]);

        if ($request->wantsJson()) {
            return response()->json($link, 201);
        }

        return back()->with('success', 'Enlace añadido correctamente.');
    }

    public function update(Request $request, Project $project, ProjectLink $link): JsonResponse|RedirectResponse
    {
        $this->authorize('manageLinks', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2000'],
            'type' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $link->update($validated);

        if ($request->wantsJson()) {
            return response()->json($link->fresh());
        }

        return back()->with('success', 'Enlace actualizado correctamente.');
    }

    public function destroy(Request $request, Project $project, ProjectLink $link): JsonResponse|RedirectResponse
    {
        $this->authorize('manageLinks', $project);

        $link->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return back()->with('success', 'Enlace eliminado correctamente.');
    }

    public function reorder(Request $request, Project $project): JsonResponse
    {
        $this->authorize('manageLinks', $project);

        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:project_links,id'],
        ]);

        foreach ($request->input('order') as $position => $linkId) {
            ProjectLink::where('id', $linkId)
                ->where('project_id', $project->id)
                ->update(['order' => $position]);
        }

        return response()->json(['message' => 'Orden actualizado']);
    }
}
