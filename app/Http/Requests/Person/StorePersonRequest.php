<?php

declare(strict_types=1);

namespace App\Http\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StorePersonRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'birthday' => ['nullable', 'date', 'before:today'],
            'birthday_reminder' => ['boolean'],
            'reminder_days_before' => ['nullable', 'integer', 'min:1', 'max:365'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => [
                'integer',
                Rule::exists('tags', 'id')->where('user_id', $this->user()->id),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'birthday.before' => 'La fecha de cumpleaños debe ser anterior a hoy.',
        ];
    }
}
