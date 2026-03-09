<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Collection;

final class DashboardService
{
    /**
     * @return array<string, mixed>
     */
    public function getStats(User $user): array
    {
        return [
            'projects' => $this->getProjectStats($user),
            'activity' => $this->getActivityStats($user),
            'upcoming' => $this->getUpcomingDeadlines($user),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getProjectStats(User $user): array
    {
        $projects = Project::forUser($user->id);

        return [
            'total' => (clone $projects)->count(),
            'active' => (clone $projects)->active()->count(),
            'archived' => (clone $projects)->archived()->count(),
            'overdue' => (clone $projects)->overdue()->count(),
            'completed_this_month' => (clone $projects)
                ->whereNotNull('completed_at')
                ->whereMonth('completed_at', now()->month)
                ->whereYear('completed_at', now()->year)
                ->count(),
            'by_status' => $this->getProjectsByStatus($user),
            'by_category' => $this->getProjectsByCategory($user),
        ];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function getProjectsByStatus(User $user): Collection
    {
        /** @var Collection<int, array<string, mixed>> */
        return Project::forUser($user->id)
            ->active()
            ->selectRaw('status_id, COUNT(*) as projects_count')
            ->groupBy('status_id')
            ->with('status:id,name,color')
            ->get()
            ->map(fn ($item) => [
                'status' => $item->status->name,
                'color' => $item->status->color,
                'count' => $item->getAttribute('projects_count'),
            ]);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function getProjectsByCategory(User $user): Collection
    {
        /** @var Collection<int, array<string, mixed>> */
        return Project::forUser($user->id)
            ->active()
            ->whereNotNull('category_id')
            ->with('category')
            ->selectRaw('category_id, COUNT(*) as projects_count')
            ->groupBy('category_id')
            ->get()
            ->map(fn ($item) => [
                'category' => $item->category?->name,
                'count' => $item->getAttribute('projects_count'),
            ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function getActivityStats(User $user): array
    {
        $thirtyDaysAgo = now()->subDays(30);

        return [
            'diary_entries_this_month' => $user->projects()
                ->withCount(['diaryEntries' => fn ($q) => $q->where('created_at', '>=', $thirtyDaysAgo)])
                ->get()
                ->sum('diary_entries_count'),
            'files_uploaded_this_month' => $user->projects()
                ->withCount(['files' => fn ($q) => $q->where('created_at', '>=', $thirtyDaysAgo)])
                ->get()
                ->sum('files_count'),
            'total_time_spent' => $user->projects()
                ->with('diaryEntries:project_id,time_spent_minutes')
                ->get()
                ->sum(fn ($p) => $p->diaryEntries->sum('time_spent_minutes')),
        ];
    }

    /**
     * @return Collection<int, Project>
     */
    private function getUpcomingDeadlines(User $user, int $limit = 5): Collection
    {
        return Project::forUser($user->id)
            ->active()
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now())
            ->whereHas('status', fn ($q) => $q->where('is_completed', false))
            ->orderBy('due_date')
            ->limit($limit)
            ->with('status:id,name,color')
            ->get(['id', 'title', 'slug', 'due_date', 'status_id']);
    }
}
