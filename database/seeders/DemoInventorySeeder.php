<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\InventoryLoan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoInventorySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'admin@builddiary.test')->first();

        if (! $user) {
            return;
        }

        $items = [
            // Herramientas manuales
            [
                'name' => 'Martillo de carpintero',
                'category' => 'tool',
                'quantity' => 2,
                'unit' => 'uds',
                'min_quantity' => 1,
                'location' => 'Panel de herramientas',
                'brand' => 'Stanley',
                'model' => 'FatMax',
                'condition' => 'good',
                'purchase_price' => 24.90,
            ],
            [
                'name' => 'Juego de destornilladores',
                'category' => 'tool',
                'quantity' => 1,
                'unit' => 'set',
                'location' => 'Cajón 1',
                'brand' => 'Wera',
                'model' => 'Kraftform Plus',
                'condition' => 'new',
                'purchase_price' => 45.00,
            ],
            [
                'name' => 'Sierra de mano',
                'category' => 'tool',
                'quantity' => 1,
                'unit' => 'uds',
                'location' => 'Panel de herramientas',
                'brand' => 'Bahco',
                'condition' => 'good',
                'purchase_price' => 18.50,
            ],
            [
                'name' => 'Sargentos 60cm',
                'category' => 'tool',
                'quantity' => 4,
                'unit' => 'uds',
                'min_quantity' => 2,
                'location' => 'Estantería B',
                'brand' => 'Wolfcraft',
                'condition' => 'good',
                'purchase_price' => 15.90,
            ],
            [
                'name' => 'Formones (juego)',
                'category' => 'tool',
                'quantity' => 1,
                'unit' => 'set',
                'location' => 'Cajón 2',
                'brand' => 'Kirschen',
                'condition' => 'good',
                'purchase_price' => 65.00,
            ],
            [
                'name' => 'Cepillo manual',
                'category' => 'tool',
                'quantity' => 1,
                'unit' => 'uds',
                'location' => 'Estantería A',
                'brand' => 'Stanley',
                'model' => 'No. 4',
                'condition' => 'fair',
                'purchase_price' => 85.00,
                'is_lent' => true,
                'lent_to' => 'Juan García',
                'lent_at' => now()->subDays(15),
            ],

            // Herramientas eléctricas
            [
                'name' => 'Taladro atornillador',
                'category' => 'equipment',
                'quantity' => 1,
                'unit' => 'uds',
                'location' => 'Armario eléctricos',
                'brand' => 'Makita',
                'model' => 'DDF453',
                'serial_number' => 'MK-2024-78432',
                'condition' => 'good',
                'purchase_price' => 189.00,
                'purchase_date' => now()->subMonths(8),
            ],
            [
                'name' => 'Sierra circular',
                'category' => 'equipment',
                'quantity' => 1,
                'unit' => 'uds',
                'location' => 'Armario eléctricos',
                'brand' => 'Bosch',
                'model' => 'GKS 190',
                'condition' => 'good',
                'purchase_price' => 145.00,
                'purchase_date' => now()->subYears(2),
            ],
            [
                'name' => 'Lijadora orbital',
                'category' => 'equipment',
                'quantity' => 1,
                'unit' => 'uds',
                'location' => 'Armario eléctricos',
                'brand' => 'Festool',
                'model' => 'ETS 125',
                'condition' => 'new',
                'purchase_price' => 295.00,
                'purchase_date' => now()->subWeeks(3),
            ],
            [
                'name' => 'Fresadora',
                'category' => 'equipment',
                'quantity' => 1,
                'unit' => 'uds',
                'location' => 'Mesa de trabajo',
                'brand' => 'Makita',
                'model' => 'RT0700C',
                'condition' => 'good',
                'purchase_price' => 175.00,
            ],

            // Materiales
            [
                'name' => 'Tablero contrachapado 15mm',
                'category' => 'material',
                'quantity' => 3,
                'unit' => 'uds',
                'min_quantity' => 2,
                'location' => 'Almacén madera',
                'condition' => 'new',
                'purchase_price' => 45.00,
            ],
            [
                'name' => 'Listones pino 45x45mm',
                'category' => 'material',
                'quantity' => 8,
                'unit' => 'm',
                'min_quantity' => 5,
                'location' => 'Almacén madera',
                'condition' => 'new',
                'purchase_price' => 3.50,
            ],
            [
                'name' => 'Tablero MDF 16mm',
                'category' => 'material',
                'quantity' => 1,
                'unit' => 'uds',
                'min_quantity' => 2,
                'location' => 'Almacén madera',
                'condition' => 'new',
                'purchase_price' => 28.00,
            ],

            // Consumibles
            [
                'name' => 'Tornillos 4x40mm',
                'category' => 'consumable',
                'quantity' => 150,
                'unit' => 'uds',
                'min_quantity' => 50,
                'location' => 'Cajón tornillería',
                'brand' => 'Spax',
                'condition' => 'new',
                'purchase_price' => 12.50,
            ],
            [
                'name' => 'Tornillos 4x60mm',
                'category' => 'consumable',
                'quantity' => 80,
                'unit' => 'uds',
                'min_quantity' => 30,
                'location' => 'Cajón tornillería',
                'brand' => 'Spax',
                'condition' => 'new',
                'purchase_price' => 14.90,
            ],
            [
                'name' => 'Cola de carpintero D3',
                'category' => 'consumable',
                'quantity' => 2,
                'unit' => 'botes',
                'min_quantity' => 1,
                'location' => 'Estantería química',
                'brand' => 'Titebond',
                'condition' => 'new',
                'purchase_price' => 12.00,
            ],
            [
                'name' => 'Lijas 120 (pack)',
                'category' => 'consumable',
                'quantity' => 0,
                'unit' => 'packs',
                'min_quantity' => 2,
                'location' => 'Cajón lijado',
                'condition' => 'new',
                'purchase_price' => 8.50,
            ],
            [
                'name' => 'Lijas 180 (pack)',
                'category' => 'consumable',
                'quantity' => 1,
                'unit' => 'packs',
                'min_quantity' => 2,
                'location' => 'Cajón lijado',
                'condition' => 'new',
                'purchase_price' => 8.50,
            ],
            [
                'name' => 'Aceite de linaza',
                'category' => 'consumable',
                'quantity' => 1,
                'unit' => 'l',
                'min_quantity' => 0.5,
                'location' => 'Estantería química',
                'brand' => 'Biofa',
                'condition' => 'new',
                'purchase_price' => 18.00,
            ],

            // Seguridad
            [
                'name' => 'Gafas de protección',
                'category' => 'safety',
                'quantity' => 2,
                'unit' => 'uds',
                'min_quantity' => 1,
                'location' => 'Perchero entrada',
                'brand' => '3M',
                'condition' => 'good',
                'purchase_price' => 12.00,
            ],
            [
                'name' => 'Mascarillas FFP2',
                'category' => 'safety',
                'quantity' => 5,
                'unit' => 'uds',
                'min_quantity' => 10,
                'location' => 'Cajón seguridad',
                'condition' => 'new',
                'purchase_price' => 2.50,
            ],
            [
                'name' => 'Protectores auditivos',
                'category' => 'safety',
                'quantity' => 1,
                'unit' => 'uds',
                'location' => 'Perchero entrada',
                'brand' => 'Peltor',
                'model' => 'X4A',
                'condition' => 'good',
                'purchase_price' => 28.00,
            ],
            [
                'name' => 'Guantes de trabajo',
                'category' => 'safety',
                'quantity' => 2,
                'unit' => 'pares',
                'min_quantity' => 2,
                'location' => 'Perchero entrada',
                'condition' => 'fair',
                'purchase_price' => 8.00,
            ],
        ];

        foreach ($items as $item) {
            InventoryItem::create([
                'user_id' => $user->id,
                'slug' => Str::slug($item['name']),
                ...$item,
            ]);
        }

        // Crear historial de préstamos
        $this->createLoanHistory($user);
    }

    private function createLoanHistory(User $user): void
    {
        // Préstamo activo del cepillo manual (ya marcado como prestado)
        $cepillo = InventoryItem::where('user_id', $user->id)
            ->where('name', 'Cepillo manual')
            ->first();

        if ($cepillo) {
            InventoryLoan::create([
                'inventory_item_id' => $cepillo->id,
                'user_id' => $user->id,
                'borrower_name' => 'Juan García',
                'borrower_contact' => '666 123 456',
                'lent_at' => now()->subDays(15),
                'expected_return_at' => now()->addDays(5),
                'condition_at_loan' => 'fair',
                'notes' => 'Necesita para un trabajo de restauración',
            ]);
        }

        // Préstamos históricos (ya devueltos)
        $taladro = InventoryItem::where('user_id', $user->id)
            ->where('name', 'Taladro atornillador')
            ->first();

        if ($taladro) {
            // Préstamo completado hace 2 meses
            InventoryLoan::create([
                'inventory_item_id' => $taladro->id,
                'user_id' => $user->id,
                'borrower_name' => 'María López',
                'borrower_contact' => 'maria@email.com',
                'lent_at' => now()->subMonths(2)->subDays(10),
                'expected_return_at' => now()->subMonths(2),
                'returned_at' => now()->subMonths(2)->addDays(2),
                'condition_at_loan' => 'good',
                'condition_at_return' => 'good',
                'notes' => 'Para montar estanterías',
            ]);

            // Préstamo completado hace 6 meses
            InventoryLoan::create([
                'inventory_item_id' => $taladro->id,
                'user_id' => $user->id,
                'borrower_name' => 'Pedro Sánchez',
                'lent_at' => now()->subMonths(6),
                'returned_at' => now()->subMonths(6)->addDays(7),
                'condition_at_loan' => 'good',
                'condition_at_return' => 'good',
            ]);
        }

        // Préstamo vencido de la lijadora
        $lijadora = InventoryItem::where('user_id', $user->id)
            ->where('name', 'Lijadora excéntrica')
            ->first();

        if ($lijadora) {
            InventoryLoan::create([
                'inventory_item_id' => $lijadora->id,
                'user_id' => $user->id,
                'borrower_name' => 'Carlos Ruiz',
                'borrower_contact' => '655 987 321',
                'lent_at' => now()->subDays(20),
                'expected_return_at' => now()->subDays(5),
                'returned_at' => now()->subDays(3),
                'condition_at_loan' => 'good',
                'condition_at_return' => 'good',
                'notes' => "Se retrasó unos días en devolver.\n\nDevolución: Todo en orden, funcionando perfectamente.",
            ]);
        }

        // Préstamo de sargentos
        $sargentos = InventoryItem::where('user_id', $user->id)
            ->where('name', 'Sargentos 60cm')
            ->first();

        if ($sargentos) {
            InventoryLoan::create([
                'inventory_item_id' => $sargentos->id,
                'user_id' => $user->id,
                'borrower_name' => 'Ana Martínez',
                'lent_at' => now()->subMonths(1),
                'returned_at' => now()->subDays(20),
                'condition_at_loan' => 'good',
                'condition_at_return' => 'good',
                'notes' => 'Prestados 2 sargentos para encolado de mesa',
            ]);
        }
    }
}
