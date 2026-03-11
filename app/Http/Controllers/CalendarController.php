<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\CalendarEventDTO;
use App\Http\Requests\Calendar\StoreCalendarEventRequest;
use App\Models\CalendarEvent;
use App\Services\Calendar\CalendarService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CalendarController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly CalendarService $calendarService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        $year = $request->integer('year', now()->year);
        $month = $request->integer('month', now()->month);

        $events = $this->calendarService->getEventsForMonth($request->user(), $year, $month);

        if ($request->wantsJson()) {
            return response()->json([
                'year' => $year,
                'month' => $month,
                'events' => $events,
            ]);
        }

        return view('calendar.index', compact('events', 'year', 'month'));
    }

    public function upcoming(Request $request): JsonResponse
    {
        $days = $request->integer('days', 30);

        $events = $this->calendarService->getUpcomingEvents($request->user(), $days);

        return response()->json($events);
    }

    public function store(StoreCalendarEventRequest $request): RedirectResponse|JsonResponse
    {
        $this->authorize('create', CalendarEvent::class);

        $dto = CalendarEventDTO::fromArray($request->validated());

        $event = CalendarEvent::create([
            'user_id' => $request->user()->id,
            'project_id' => $dto->projectId,
            'person_id' => $dto->personId,
            'title' => $dto->title,
            'description' => $dto->description,
            'type' => $dto->type,
            'event_date' => $dto->eventDate,
            'event_time' => $dto->eventTime,
            'end_date' => $dto->endDate,
            'is_all_day' => $dto->isAllDay,
            'is_recurring' => $dto->isRecurring,
            'recurrence_rule' => $dto->recurrenceRule,
            'color' => $dto->color,
            'reminder_enabled' => $dto->reminderEnabled,
            'reminder_minutes_before' => $dto->reminderMinutesBefore,
        ]);

        if ($request->wantsJson()) {
            return response()->json($event, 201);
        }

        return back()->with('success', 'Evento creado correctamente.');
    }

    public function show(Request $request, CalendarEvent $event): JsonResponse
    {
        $this->authorize('view', $event);

        $event->load(['project', 'person']);

        return response()->json($event);
    }

    public function update(StoreCalendarEventRequest $request, CalendarEvent $event): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $event);

        $dto = CalendarEventDTO::fromArray($request->validated());

        $event->update([
            'project_id' => $dto->projectId,
            'person_id' => $dto->personId,
            'title' => $dto->title,
            'description' => $dto->description,
            'type' => $dto->type,
            'event_date' => $dto->eventDate,
            'event_time' => $dto->eventTime,
            'end_date' => $dto->endDate,
            'is_all_day' => $dto->isAllDay,
            'is_recurring' => $dto->isRecurring,
            'recurrence_rule' => $dto->recurrenceRule,
            'color' => $dto->color,
            'reminder_enabled' => $dto->reminderEnabled,
            'reminder_minutes_before' => $dto->reminderMinutesBefore,
        ]);

        if ($request->wantsJson()) {
            return response()->json($event->fresh());
        }

        return back()->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Request $request, CalendarEvent $event): RedirectResponse|JsonResponse
    {
        $this->authorize('delete', $event);

        $event->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return back()->with('success', 'Evento eliminado correctamente.');
    }
}
