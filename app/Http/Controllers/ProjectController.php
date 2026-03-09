<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\ProjectData;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use App\Services\Project\ArchiveProjectAction;
use App\Services\Project\CreateProjectAction;
use App\Services\Project\DeleteProjectAction;
use App\Services\Project\UpdateProjectAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly CreateProjectAction $createAction,
        private readonly UpdateProjectAction $updateAction,
        private readonly DeleteProjectAction $deleteAction,
        private readonly ArchiveProjectAction $archiveAction,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        $this->authorize('viewAny', Project::class);

        $projects = Project::forUser($request->user()->id)
            ->with(['status', 'person', 'tags'])
            ->when($request->filled('status'), fn ($q) => $q->withStatus($request->integer('status')))
            ->when($request->filled('archived'), fn ($q) => $request->boolean('archived') ? $q->archived() : $q->active())
            ->when(! $request->filled('archived'), fn ($q) => $q->active())
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('tags'), fn ($q) => $q->withAnyTags($request->input('tags')))
            ->orderByDesc('updated_at')
            ->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($projects);
        }

        return view('projects.index', compact('projects'));
    }

    public function show(Request $request, Project $project): View|JsonResponse
    {
        $this->authorize('view', $project);

        $project->load(['status', 'person', 'tags', 'files', 'diaryEntries', 'links']);

        if ($request->wantsJson()) {
            return response()->json($project);
        }

        return view('projects.show', compact('project'));
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);

        return view('projects.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse|JsonResponse
    {
        $this->authorize('create', Project::class);

        $project = $this->createAction->execute(
            $request->user(),
            ProjectData::fromArray($request->validated())
        );

        if ($request->wantsJson()) {
            return response()->json($project, 201);
        }

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Proyecto creado correctamente.');
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        $project->load(['status', 'person', 'tags']);

        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $project);

        $project = $this->updateAction->execute(
            $project,
            ProjectData::fromArray($request->validated())
        );

        if ($request->wantsJson()) {
            return response()->json($project);
        }

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Request $request, Project $project): RedirectResponse|JsonResponse
    {
        $this->authorize('delete', $project);

        $this->deleteAction->execute($project);

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proyecto eliminado correctamente.');
    }

    public function archive(Request $request, Project $project): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $project);

        $this->archiveAction->archive($project);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Proyecto archivado']);
        }

        return back()->with('success', 'Proyecto archivado correctamente.');
    }

    public function unarchive(Request $request, Project $project): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $project);

        $this->archiveAction->unarchive($project);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Proyecto desarchivado']);
        }

        return back()->with('success', 'Proyecto desarchivado correctamente.');
    }
}
