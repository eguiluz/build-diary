<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\User;
use App\Services\ProjectImportService;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * @property Form $form
 */
class ImportProject extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationLabel = 'Importar proyecto';

    protected static ?string $title = 'Importar proyecto';

    protected static ?string $navigationGroup = 'Proyectos';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.import-project';

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Importar proyecto')
                    ->description('Sube un archivo ZIP exportado desde Build Diary para importar un proyecto completo con todos sus datos.')
                    ->schema([
                        Forms\Components\FileUpload::make('file')
                            ->label('Archivo ZIP')
                            ->required()
                            ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                            ->maxSize(102400) // 100MB
                            ->disk('local')
                            ->directory('temp/imports')
                            ->helperText('Selecciona un archivo .zip exportado previamente. Máximo 100MB.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function import(): void
    {
        $data = $this->form->getState();

        if (empty($data['file'])) {
            Notification::make()
                ->title('Error')
                ->body('Por favor, selecciona un archivo ZIP.')
                ->danger()
                ->send();

            return;
        }

        try {
            /** @var User $user */
            $user = Auth::user();

            $filePath = Storage::disk('local')->path($data['file']);

            $importer = new ProjectImportService;
            $project = $importer->import($filePath, $user);

            // Clean up uploaded file
            Storage::disk('local')->delete($data['file']);

            // Reset form
            $this->form->fill();

            Notification::make()
                ->title('Proyecto importado')
                ->body("El proyecto \"{$project->title}\" ha sido importado correctamente.")
                ->success()
                ->send();

            // Redirect to the imported project
            $this->redirect(route('filament.admin.resources.projects.edit', $project));
        } catch (\Exception $e) {
            // Clean up uploaded file on error
            /** @var string|null $uploadedFile */
            $uploadedFile = $data['file'];
            if ($uploadedFile) {
                Storage::disk('local')->delete($uploadedFile);
            }

            Notification::make()
                ->title('Error al importar')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
