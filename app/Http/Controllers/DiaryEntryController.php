<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\DiaryEntryDTO;
use App\Http\Requests\Diary\StoreDiaryEntryRequest;
use App\Http\Requests\Diary\UpdateDiaryEntryRequest;
use App\Models\DiaryEntry;
use App\Models\Project;
use App\Services\Diary\CreateDiaryEntryAction;
use App\Services\Diary\UpdateDiaryEntryAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class DiaryEntryController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly CreateDiaryEntryAction $createAction,
        private readonly UpdateDiaryEntryAction $updateAction,
    ) {}

    public function index(Request $request, Project $project): JsonResponse
    {
        $this->authorize('manageDiary', $project);

        $entries = $project->diaryEntries()
            ->when($request->filled('type'), fn ($q) => $q->ofType((string) $request->string('type')))
            ->when($request->filled('from'), fn ($q) => $q->where('entry_date', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('entry_date', '<=', $request->date('to')))
            ->orderByDesc('entry_date')
            ->orderByDesc('entry_time')
            ->paginate(20);

        return response()->json($entries);
    }

    public function store(StoreDiaryEntryRequest $request, Project $project): JsonResponse|RedirectResponse
    {
        $this->authorize('manageDiary', $project);

        $entry = $this->createAction->execute(
            $project,
            DiaryEntryDTO::fromArray($request->validated())
        );

        if ($request->wantsJson()) {
            return response()->json($entry, 201);
        }

        return back()->with('success', 'Entrada añadida correctamente.');
    }

    public function show(Project $project, DiaryEntry $entry): JsonResponse
    {
        $this->authorize('view', $entry);

        return response()->json($entry);
    }

    public function update(UpdateDiaryEntryRequest $request, Project $project, DiaryEntry $entry): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $entry);

        $entry = $this->updateAction->execute(
            $entry,
            DiaryEntryDTO::fromArray($request->validated())
        );

        if ($request->wantsJson()) {
            return response()->json($entry);
        }

        return back()->with('success', 'Entrada actualizada correctamente.');
    }

    public function destroy(Request $request, Project $project, DiaryEntry $entry): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $entry);

        $entry->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return back()->with('success', 'Entrada eliminada correctamente.');
    }
}
