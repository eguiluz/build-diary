<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Project\UploadProjectFilesRequest;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Services\File\DeleteProjectFileAction;
use App\Services\File\UploadProjectFileAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ProjectFileController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly UploadProjectFileAction $uploadAction,
        private readonly DeleteProjectFileAction $deleteAction,
    ) {}

    public function index(Request $request, Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $files = $project->files()
            ->when($request->filled('type'), fn ($q) => $q->ofType((string) $request->string('type')))
            ->orderBy('order')
            ->get();

        return response()->json($files);
    }

    public function store(UploadProjectFilesRequest $request, Project $project): JsonResponse|RedirectResponse
    {
        $this->authorize('uploadFiles', $project);

        $files = $this->uploadAction->executeMultiple(
            $project,
            $request->file('files')
        );

        if ($request->wantsJson()) {
            return response()->json($files, 201);
        }

        return back()->with('success', count($files).' archivo(s) subido(s) correctamente.');
    }

    public function show(Project $project, ProjectFile $file): JsonResponse
    {
        $this->authorize('view', $file);

        return response()->json($file);
    }

    public function download(Project $project, ProjectFile $file): StreamedResponse
    {
        $this->authorize('download', $file);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($file->disk);

        return $disk->download(
            $file->path,
            $file->original_name
        );
    }

    public function destroy(Request $request, Project $project, ProjectFile $file): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $file);

        $this->deleteAction->execute($file);

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return back()->with('success', 'Archivo eliminado correctamente.');
    }

    public function bulkDelete(Request $request, Project $project): JsonResponse|RedirectResponse
    {
        $this->authorize('uploadFiles', $project);

        $request->validate([
            'file_ids' => ['required', 'array', 'min:1'],
            'file_ids.*' => ['integer', 'exists:project_files,id'],
        ]);

        $fileIds = $request->input('file_ids');

        // Verify all files belong to this project
        $files = ProjectFile::whereIn('id', $fileIds)
            ->where('project_id', $project->id)
            ->get();

        foreach ($files as $file) {
            $this->deleteAction->execute($file);
        }

        if ($request->wantsJson()) {
            return response()->json(['deleted' => $files->count()]);
        }

        return back()->with('success', $files->count().' archivo(s) eliminado(s).');
    }
}
