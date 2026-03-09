<?php

declare(strict_types=1);

namespace App\Http\Requests\Diary;

use App\Models\DiaryEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateDiaryEntryRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:50000'],
            'type' => ['required', 'string', Rule::in(array_keys(DiaryEntry::getTypes()))],
            'entry_date' => ['nullable', 'date'],
            'entry_time' => ['nullable', 'date_format:H:i'],
            'time_spent_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
