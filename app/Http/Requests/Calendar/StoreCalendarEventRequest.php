<?php

declare(strict_types=1);

namespace App\Http\Requests\Calendar;

use App\Models\CalendarEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreCalendarEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'type' => ['required', 'string', Rule::in(array_keys(CalendarEvent::getTypes()))],
            'event_date' => ['required', 'date'],
            'event_time' => ['nullable', 'date_format:H:i'],
            'end_date' => ['nullable', 'date', 'after_or_equal:event_date'],
            'is_all_day' => ['boolean'],
            'is_recurring' => ['boolean'],
            'recurrence_rule' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'reminder_enabled' => ['boolean'],
            'reminder_minutes_before' => ['nullable', 'integer', 'min:0', 'max:10080'],
            'project_id' => [
                'nullable',
                'integer',
                Rule::exists('projects', 'id')->where('user_id', $this->user()->id),
            ],
            'person_id' => [
                'nullable',
                'integer',
                Rule::exists('people', 'id')->where('user_id', $this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('app.validation.title_required'),
            'event_date.required' => __('app.validation.event_date_required'),
            'end_date.after_or_equal' => __('app.validation.end_date_after'),
            'color.regex' => __('app.validation.color_hex'),
        ];
    }
}
