<?php

declare(strict_types=1);

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreTagRequest extends FormRequest
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
                    ->where('user_id', $this->user()->id),
            ],
            'color' => ['nullable', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la etiqueta es obligatorio.',
            'name.unique' => 'Ya existe una etiqueta con ese nombre.',
            'color.regex' => 'El color debe ser un código hexadecimal válido.',
        ];
    }
}
