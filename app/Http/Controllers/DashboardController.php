<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Dashboard\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    public function __invoke(Request $request): View|JsonResponse
    {
        $stats = $this->dashboardService->getStats($request->user());

        if ($request->wantsJson()) {
            return response()->json($stats);
        }

        return view('dashboard', compact('stats'));
    }
}
