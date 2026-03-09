<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectExpense;
use Illuminate\Database\Seeder;

class DemoProjectExpensesSeeder extends Seeder
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $materials = [
        ['name' => 'Tablero de pino macizo', 'unit' => 'ud', 'min_price' => 40, 'max_price' => 120],
        ['name' => 'Listones de madera', 'unit' => 'ud', 'min_price' => 8, 'max_price' => 25],
        ['name' => 'Tablero MDF', 'unit' => 'ud', 'min_price' => 15, 'max_price' => 45],
        ['name' => 'Contrachapado', 'unit' => 'ud', 'min_price' => 20, 'max_price' => 60],
        ['name' => 'Tornillos (caja)', 'unit' => 'caja', 'min_price' => 5, 'max_price' => 15],
        ['name' => 'Clavos surtidos', 'unit' => 'caja', 'min_price' => 3, 'max_price' => 10],
        ['name' => 'Bisagras', 'unit' => 'par', 'min_price' => 4, 'max_price' => 20],
        ['name' => 'Tiradores', 'unit' => 'ud', 'min_price' => 3, 'max_price' => 15],
        ['name' => 'Tubo de PVC', 'unit' => 'm', 'min_price' => 2, 'max_price' => 8],
        ['name' => 'Cable eléctrico', 'unit' => 'm', 'min_price' => 1, 'max_price' => 4],
        ['name' => 'Cinta LED', 'unit' => 'm', 'min_price' => 5, 'max_price' => 15],
        ['name' => 'Aislante térmico', 'unit' => 'm²', 'min_price' => 8, 'max_price' => 25],
        ['name' => 'Pintura (bote)', 'unit' => 'ud', 'min_price' => 15, 'max_price' => 45],
        ['name' => 'Barniz protector', 'unit' => 'ud', 'min_price' => 12, 'max_price' => 35],
        ['name' => 'Masilla de relleno', 'unit' => 'ud', 'min_price' => 5, 'max_price' => 15],
        ['name' => 'Silicona', 'unit' => 'ud', 'min_price' => 4, 'max_price' => 12],
        ['name' => 'Cemento rápido', 'unit' => 'kg', 'min_price' => 3, 'max_price' => 8],
        ['name' => 'Tela tapicería', 'unit' => 'm', 'min_price' => 10, 'max_price' => 35],
        ['name' => 'Espuma alta densidad', 'unit' => 'ud', 'min_price' => 15, 'max_price' => 50],
        ['name' => 'Cristal templado', 'unit' => 'ud', 'min_price' => 25, 'max_price' => 80],
    ];

    /**
     * @var array<int, array<string, mixed>>
     */
    private array $tools = [
        ['name' => 'Sargentos de carpintero', 'unit' => 'ud', 'min_price' => 10, 'max_price' => 30],
        ['name' => 'Escuadra de carpintero', 'unit' => 'ud', 'min_price' => 12, 'max_price' => 25],
        ['name' => 'Nivel de burbuja', 'unit' => 'ud', 'min_price' => 8, 'max_price' => 20],
        ['name' => 'Flexómetro 5m', 'unit' => 'ud', 'min_price' => 5, 'max_price' => 15],
        ['name' => 'Juego de brocas', 'unit' => 'set', 'min_price' => 15, 'max_price' => 40],
        ['name' => 'Disco de sierra', 'unit' => 'ud', 'min_price' => 10, 'max_price' => 35],
        ['name' => 'Formón', 'unit' => 'ud', 'min_price' => 8, 'max_price' => 25],
        ['name' => 'Cepillo manual', 'unit' => 'ud', 'min_price' => 15, 'max_price' => 45],
        ['name' => 'Pistola de silicona', 'unit' => 'ud', 'min_price' => 8, 'max_price' => 20],
        ['name' => 'Juego de llaves Allen', 'unit' => 'set', 'min_price' => 8, 'max_price' => 20],
    ];

    /**
     * @var array<int, array<string, mixed>>
     */
    private array $consumables = [
        ['name' => 'Lijas surtidas (pack)', 'unit' => 'pack', 'min_price' => 4, 'max_price' => 12],
        ['name' => 'Cola de carpintero', 'unit' => 'ud', 'min_price' => 5, 'max_price' => 15],
        ['name' => 'Cinta de carrocero', 'unit' => 'ud', 'min_price' => 2, 'max_price' => 6],
        ['name' => 'Discos de lija', 'unit' => 'pack', 'min_price' => 6, 'max_price' => 15],
        ['name' => 'Aceite de linaza', 'unit' => 'ud', 'min_price' => 10, 'max_price' => 20],
        ['name' => 'Cera para madera', 'unit' => 'ud', 'min_price' => 8, 'max_price' => 18],
        ['name' => 'Trapos de limpieza', 'unit' => 'pack', 'min_price' => 3, 'max_price' => 8],
        ['name' => 'Guantes de trabajo', 'unit' => 'par', 'min_price' => 3, 'max_price' => 10],
        ['name' => 'Mascarilla FFP2', 'unit' => 'pack', 'min_price' => 5, 'max_price' => 15],
        ['name' => 'Gafas de protección', 'unit' => 'ud', 'min_price' => 5, 'max_price' => 15],
    ];

    /**
     * @var array<int, array<string, mixed>>
     */
    private array $services = [
        ['name' => 'Corte a medida', 'unit' => 'cortes', 'min_price' => 2, 'max_price' => 5],
        ['name' => 'Transporte materiales', 'unit' => 'viaje', 'min_price' => 15, 'max_price' => 40],
        ['name' => 'Alquiler herramienta', 'unit' => 'día', 'min_price' => 10, 'max_price' => 30],
        ['name' => 'Asesoría técnica', 'unit' => 'hora', 'min_price' => 20, 'max_price' => 50],
    ];

    /**
     * @var array<int, array<string, mixed>>
     */
    private array $other = [
        ['name' => 'Ruedas con freno', 'unit' => 'ud', 'min_price' => 6, 'max_price' => 15],
        ['name' => 'Patas niveladoras', 'unit' => 'ud', 'min_price' => 2, 'max_price' => 8],
        ['name' => 'Organizador magnético', 'unit' => 'ud', 'min_price' => 10, 'max_price' => 25],
        ['name' => 'Iluminación LED', 'unit' => 'ud', 'min_price' => 15, 'max_price' => 40],
        ['name' => 'Enchufe empotrado', 'unit' => 'ud', 'min_price' => 8, 'max_price' => 20],
    ];

    /**
     * @var array<int, string>
     */
    private array $suppliers = [
        'Leroy Merlin',
        'Bricomart',
        'Amazon',
        'AKI',
        'Bauhaus',
        'Ikea',
        'Ferretería local',
    ];

    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            $this->generateExpensesForProject($project);
        }
    }

    private function generateExpensesForProject(Project $project): void
    {
        $usedItems = [];

        // Always add some materials (2-5)
        $numMaterials = random_int(2, 5);
        $this->addRandomExpenses($project, 'material', $this->materials, $numMaterials, $usedItems);

        // Add some tools (1-3)
        $numTools = random_int(1, 3);
        $this->addRandomExpenses($project, 'tool', $this->tools, $numTools, $usedItems);

        // Add consumables (1-4)
        $numConsumables = random_int(1, 4);
        $this->addRandomExpenses($project, 'consumable', $this->consumables, $numConsumables, $usedItems);

        // Maybe add services (0-2)
        if (random_int(0, 1) === 1) {
            $numServices = random_int(1, 2);
            $this->addRandomExpenses($project, 'service', $this->services, $numServices, $usedItems);
        }

        // Maybe add other (0-2)
        if (random_int(0, 1) === 1) {
            $numOther = random_int(1, 2);
            $this->addRandomExpenses($project, 'other', $this->other, $numOther, $usedItems);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @param  array<string, bool>  $usedItems
     */
    private function addRandomExpenses(Project $project, string $category, array $items, int $count, array &$usedItems): void
    {
        $availableItems = array_filter($items, fn ($item) => ! isset($usedItems[$item['name']]));

        if (empty($availableItems)) {
            return;
        }

        $availableItemsIndexed = array_values($availableItems);
        $selectCount = min($count, count($availableItemsIndexed));

        $selectedIndexes = (array) array_rand($availableItemsIndexed, max(1, $selectCount));

        foreach ($selectedIndexes as $index) {
            $item = $availableItemsIndexed[$index];
            $usedItems[$item['name']] = true;

            $isPurchased = random_int(0, 100) < 70;
            $quantity = $this->getRandomQuantity($category);
            $unitPrice = round(random_int((int) $item['min_price'], (int) $item['max_price']) + (random_int(0, 99) / 100), 2);

            ProjectExpense::create([
                'project_id' => $project->id,
                'name' => $item['name'],
                'description' => random_int(0, 1) === 1 ? $this->getRandomDescription($category) : null,
                'category' => $category,
                'quantity' => $quantity,
                'unit' => $item['unit'],
                'unit_price' => $unitPrice,
                'supplier' => random_int(0, 100) < 80 ? $this->suppliers[array_rand($this->suppliers)] : null,
                'url' => random_int(0, 100) < 30 ? 'https://example.com/product/'.random_int(1000, 9999) : null,
                'is_purchased' => $isPurchased,
                'purchased_at' => $isPurchased ? now()->subDays(random_int(1, 30)) : null,
            ]);
        }
    }

    private function getRandomQuantity(string $category): int
    {
        return match ($category) {
            'material' => random_int(1, 6),
            'tool' => random_int(1, 4),
            'consumable' => random_int(1, 3),
            'service' => random_int(1, 5),
            default => random_int(1, 4),
        };
    }

    private function getRandomDescription(string $category): string
    {
        $descriptions = [
            'material' => [
                'Para la estructura principal',
                'Refuerzo adicional',
                'Acabado exterior',
                'Base del proyecto',
                'Parte superior',
            ],
            'tool' => [
                'Necesario para el montaje',
                'Para cortes precisos',
                'Medición y ajuste',
                'Trabajo de detalle',
            ],
            'consumable' => [
                'Acabado final',
                'Preparación de superficies',
                'Protección durante el trabajo',
                'Lijado y pulido',
            ],
            'service' => [
                'Servicio en tienda',
                'Trabajo especializado',
                'Apoyo profesional',
            ],
            'other' => [
                'Accesorio opcional',
                'Mejora funcional',
                'Complemento útil',
            ],
        ];

        $categoryDescriptions = $descriptions[$category] ?? $descriptions['other'];

        return $categoryDescriptions[array_rand($categoryDescriptions)];
    }
}
