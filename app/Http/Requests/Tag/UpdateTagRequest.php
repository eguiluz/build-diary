<?php

declare(strict_types=1);

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateTagRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tags')
                    ->where('user_id', $this->user()->id)
                    ->ignore($this->route('tag')),
            ],
            'color' => ['nullable', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
        ];
    }
}
