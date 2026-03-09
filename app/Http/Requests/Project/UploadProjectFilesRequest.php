<?php

declare(strict_types=1);

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

final class UploadProjectFilesRequest extends FormRequest
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
            'files' => ['required', 'array', 'min:1', 'max:20'],
            'files.*' => [
                'required',
                'file',
                'max:51200', // 50MB
                'mimes:jpg,jpeg,png,gif,webp,pdf,stl,obj,zip,rar,7z,doc,docx,xls,xlsx,svg',
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'files.required' => 'Debes seleccionar al menos un archivo.',
            'files.max' => 'No puedes subir más de 20 archivos a la vez.',
            'files.*.max' => 'Cada archivo no puede superar los 50MB.',
            'files.*.mimes' => 'Formato de archivo no permitido.',
        ];
    }
}
