<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\PersonDTO;
use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\Person\UpdatePersonRequest;
use App\Models\Person;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PersonController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View|JsonResponse
    {
        $this->authorize('viewAny', Person::class);

        $people = Person::forUser($request->user()->id)
            ->with('tags')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('upcoming_birthdays'), fn ($q) => $q->upcomingBirthdays($request->integer('upcoming_birthdays', 30)))
            ->orderBy('name')
            ->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($people);
        }

        return view('people.index', compact('people'));
    }

    public function show(Request $request, Person $person): View|JsonResponse
    {
        $this->authorize('view', $person);

        $person->load(['tags', 'projects.status']);

        if ($request->wantsJson()) {
            return response()->json($person);
        }

        return view('people.show', compact('person'));
    }

    public function create(): View
    {
        $this->authorize('create', Person::class);

        return view('people.create');
    }

    public function store(StorePersonRequest $request): RedirectResponse|JsonResponse
    {
        $this->authorize('create', Person::class);

        $dto = PersonDTO::fromArray($request->validated());

        $person = Person::create([
            'user_id' => $request->user()->id,
            'name' => $dto->name,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'birthday' => $dto->birthday,
            'birthday_reminder' => $dto->birthdayReminder,
            'reminder_days_before' => $dto->reminderDaysBefore,
            'notes' => $dto->notes,
        ]);

        if ($dto->tagIds) {
            $person->syncTags($dto->tagIds);
        }

        if ($request->wantsJson()) {
            return response()->json($person->load('tags'), 201);
        }

        return redirect()
            ->route('people.show', $person)
            ->with('success', 'Persona creada correctamente.');
    }

    public function edit(Person $person): View
    {
        $this->authorize('update', $person);

        $person->load('tags');

        return view('people.edit', compact('person'));
    }

    public function update(UpdatePersonRequest $request, Person $person): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $person);

        $dto = PersonDTO::fromArray($request->validated());

        $person->update([
            'name' => $dto->name,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'birthday' => $dto->birthday,
            'birthday_reminder' => $dto->birthdayReminder,
            'reminder_days_before' => $dto->reminderDaysBefore,
            'notes' => $dto->notes,
        ]);

        if ($dto->tagIds !== null) {
            $person->syncTags($dto->tagIds);
        }

        if ($request->wantsJson()) {
            return response()->json($person->fresh()->load('tags'));
        }

        return redirect()
            ->route('people.show', $person)
            ->with('success', 'Persona actualizada correctamente.');
    }

    public function destroy(Request $request, Person $person): RedirectResponse|JsonResponse
    {
        $this->authorize('delete', $person);

        $person->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()
            ->route('people.index')
            ->with('success', 'Persona eliminada correctamente.');
    }
}
