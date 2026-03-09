<?php

declare(strict_types=1);

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateProjectRequest extends FormRequest
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
            'description' => ['nullable', 'string', 'max:10000'],
            'status_id' => ['required', 'integer', 'exists:project_statuses,id'],
            'person_id' => [
                'nullable',
                'integer',
                Rule::exists('people', 'id')->where('user_id', $this->user()->id),
            ],
            'category' => ['nullable', 'string', 'max:100'],
            'due_date' => ['nullable', 'date'],
            'started_at' => ['nullable', 'date'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:10'],
            'metadata' => ['nullable', 'array'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => [
                'integer',
                Rule::exists('tags', 'id')->where('user_id', $this->user()->id),
            ],
        ];
    }
}
