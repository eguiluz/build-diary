<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\DiaryEntry;
use App\Models\Person;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectLink;
use App\Models\ProjectStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoProjectsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->error('No hay usuarios. Ejecuta primero AdminUserSeeder.');

            return;
        }

        $statuses = ProjectStatus::pluck('id', 'slug');
        $categories = ProjectCategory::pluck('id', 'slug');
        $people = Person::where('user_id', $user->id)->pluck('id', 'name');

        $projects = [
            [
                'title' => 'Estantería flotante de madera de roble',
                'description' => "## Objetivo del proyecto\n\nConstruir una estantería de pared con sistema de anclaje oculto que parezca flotar. El diseño minimalista complementará el estilo nórdico del salón.\n\n## Especificaciones técnicas\n\n- **Medidas**: 120 x 25 x 3 cm\n- **Material**: Tablero de roble macizo de primera calidad\n- **Acabado**: Aceite natural danés (3 capas)\n- **Capacidad de carga**: ~30 kg distribuidos\n\n## Sistema de anclaje\n\nUtilizaré varillas de acero inoxidable de 12mm empotradas en tacos químicos. Las varillas entrarán 15cm en la pared y 12cm en la madera.\n\n## Presupuesto estimado\n\n- Tablero de roble: 85€\n- Varillas y tacos: 25€\n- Aceite danés: 18€\n- **Total**: ~130€",
                'category' => 'carpentry',
                'status_slug' => 'in-progress',
                'priority' => 3,
                'due_date' => now()->addDays(14),
                'started_at' => now()->subDays(7),
                'is_public' => true,
                'person_name' => 'María García',
                'diary_entries' => [
                    ['title' => 'Compra de materiales', 'content' => "Hoy fui al almacén de maderas Hermanos López. Encontré un tablero de roble espectacular, sin nudos y con una veta muy uniforme. El vendedor me recomendó dejarlo aclimatar una semana antes de trabajarlo.\n\nTambién compré el aceite danés y las varillas de acero. Todo listo para empezar.", 'type' => 'progress', 'time_spent' => 60, 'mood' => '😊'],
                    ['title' => 'Corte y cepillado', 'content' => "Día intenso de trabajo. Corté el tablero a medida usando la sierra de mesa con una hoja nueva de 60 dientes. El corte quedó limpio, casi sin astillas.\n\nDespués pasé la cepilladora eléctrica para igualar el grosor y eliminar las marcas de sierra. Terminé con un lijado progresivo: 80, 120, 180.\n\nLa madera ha quedado como la seda. Mañana haré los agujeros para las varillas.", 'type' => 'progress', 'time_spent' => 180, 'mood' => '💪'],
                    ['title' => 'Primera capa de aceite', 'content' => "Apliqué la primera capa de aceite con un trapo de algodón, frotando bien para que penetre en la fibra. El color del roble ha ganado profundidad, se ve precioso.\n\nAhora toca esperar 24 horas para la segunda capa. La paciencia es clave en estos acabados.", 'type' => 'note', 'time_spent' => 30, 'mood' => '🎨'],
                    ['title' => 'Problema con los taladros', 'content' => 'Al hacer los agujeros para las varillas, la broca se desvió ligeramente. El agujero no quedó perfectamente perpendicular. Tendré que rellenar con cola y virutas y volver a taladrar con una guía.', 'type' => 'issue', 'time_spent' => 45, 'mood' => '😤'],
                ],
                'links' => [
                    ['title' => 'Tutorial de anclajes ocultos - YouTube', 'url' => 'https://www.youtube.com/watch?v=example1'],
                    ['title' => 'Aceite danés para madera - Amazon', 'url' => 'https://www.amazon.es/dp/example'],
                    ['title' => 'Guía de taladrado - PDF', 'url' => 'https://www.herramientas.com/guia-taladrado.pdf'],
                ],
            ],
            [
                'title' => 'Soporte para auriculares impreso en 3D',
                'description' => "## Descripción\n\nDiseño minimalista para auriculares gaming, optimizado para impresión FDM sin soportes. El diseño incluye una base con almohadilla antideslizante y un arco ergonómico que se adapta a diferentes tamaños de auriculares.\n\n## Características\n\n- **Material**: PLA negro mate\n- **Tiempo de impresión**: ~8 horas\n- **Peso del filamento**: 95g\n- **Sin soportes necesarios**\n- **Diseño paramétrico** (ajustable en Fusion 360)\n\n## Parámetros de impresión\n\n- Altura de capa: 0.2mm\n- Relleno: 20% gyroid\n- Perímetros: 3\n- Temperatura boquilla: 210°C\n- Temperatura cama: 65°C\n\n## Archivos incluidos\n\n- STL para impresión\n- Archivo F3D editable\n- STEP para importar en otros CAD",
                'category' => '3d_printing',
                'status_slug' => 'completed',
                'priority' => 1,
                'due_date' => now()->subDays(3),
                'started_at' => now()->subDays(10),
                'completed_at' => now()->subDays(2),
                'is_public' => true,
                'person_name' => 'Carlos Rodríguez',
                'diary_entries' => [
                    ['title' => 'Diseño inicial en Fusion 360', 'content' => "Pasé toda la tarde diseñando el soporte en Fusion 360. Hice varias iteraciones hasta conseguir un ángulo de inclinación que se vea bien y mantenga los auriculares estables.\n\nEl truco estuvo en usar una curva spline para el arco en lugar de un arco simple. Da un aspecto mucho más orgánico.", 'type' => 'progress', 'time_spent' => 180, 'mood' => '🎨'],
                    ['title' => 'Primera prueba de impresión FALLIDA', 'content' => "Desastre total. La pieza se despegó de la cama a las 2 horas de impresión. Cuando llegué por la mañana había un nido de espagueti de PLA.\n\nCausas posibles:\n- Cama demasiado fría (60°C)\n- Primera capa muy rápida\n- Brim insuficiente", 'type' => 'issue', 'time_spent' => 90, 'mood' => '😞'],
                    ['title' => 'Solución al problema de adhesión', 'content' => "Después de investigar en Reddit y en el foro de Prusa, hice estos cambios:\n\n1. Subí la temperatura de la cama a 65°C\n2. Reduje la velocidad de la primera capa a 15mm/s\n3. Añadí un brim de 8mm\n4. Limpié la cama con IPA al 99%\n\n¡Funcionó! La pieza quedó perfectamente adherida durante toda la impresión.", 'type' => 'solution', 'time_spent' => 30, 'mood' => '💡'],
                    ['title' => 'Impresión final perfecta', 'content' => "8 horas de impresión sin ningún fallo. La calidad superficial es excelente, casi no se notan las capas. El negro mate queda muy elegante.\n\nYa monté los auriculares y quedan genial. Carlos va a flipar cuando se lo regale.", 'type' => 'milestone', 'time_spent' => 15, 'mood' => '🎉'],
                ],
                'links' => [
                    ['title' => 'Archivo STL en Thingiverse', 'url' => 'https://www.thingiverse.com/thing/example'],
                    ['title' => 'Settings PrusaSlicer', 'url' => 'https://github.com/settings-example'],
                    ['title' => 'Hilo de Reddit sobre adhesión', 'url' => 'https://reddit.com/r/3dprinting/example'],
                ],
            ],
            [
                'title' => 'Caja de regalo con kirigami',
                'description' => "## Proyecto especial\n\nCaja desplegable con diseño de mariposas en 3D usando técnica de kirigami. Es un regalo para el cumpleaños de Ana el próximo mes.\n\n## Técnica\n\nEl kirigami combina cortes y pliegues en papel para crear estructuras tridimensionales a partir de una hoja plana. A diferencia del origami puro, aquí sí se permiten cortes.\n\n## Materiales\n\n- Papel de 300g/m² en color marfil\n- Papel de seda rosa para forrar interior\n- Cinta de raso 10mm\n- Pegamento en barra\n\n## Dimensiones\n\n- Caja cerrada: 15 x 15 x 5 cm\n- Caja desplegada: 30 x 30 cm plano",
                'category' => 'paper_art',
                'status_slug' => 'idea',
                'priority' => 2,
                'due_date' => now()->addDays(30),
                'is_public' => false,
                'person_name' => 'Ana Martínez',
                'diary_entries' => [
                    ['title' => 'Bocetos iniciales', 'content' => "He estado buscando inspiración en Pinterest y en libros de origami arquitectónico. Quiero combinar la delicadeza de las mariposas con una estructura robusta que aguante bien el uso.\n\nHice varios bocetos a lápiz. El diseño favorito tiene 4 mariposas que se levantan cuando abres la caja, una en cada esquina.", 'type' => 'note', 'time_spent' => 45, 'mood' => '✏️'],
                ],
                'links' => [
                    ['title' => 'Patrones de kirigami - Pinterest', 'url' => 'https://www.pinterest.es/kirigami-patterns'],
                    ['title' => 'Libro: The Art of Kirigami', 'url' => 'https://www.amazon.es/art-kirigami'],
                ],
            ],
            [
                'title' => 'Lámpara LED con difusor impreso',
                'description' => "## Concepto\n\nLámpara de mesa con estructura impresa en 3D y tira LED RGB controlada por WiFi. El difusor está diseñado con un patrón de Voronoi que crea sombras interesantes en la pared.\n\n## Componentes electrónicos\n\n- ESP32-C3 (controlador WiFi)\n- Tira LED WS2812B (30 LEDs/m, 1 metro)\n- Fuente 5V 3A\n- Integración con Home Assistant\n\n## Diseño mecánico\n\n- Base: PLA blanco, 150mm diámetro\n- Columna: PETG transparente, 250mm altura\n- Difusor: PLA blanco translúcido, 0.4mm espesor\n\n## Software\n\n- Firmware: WLED\n- Control: App WLED + Home Assistant\n- Efectos predefinidos: aurora, fuego, arcoíris, respiración",
                'category' => '3d_printing',
                'status_slug' => 'in-progress',
                'priority' => 4,
                'started_at' => now()->subDays(14),
                'due_date' => now()->addDays(7),
                'is_public' => true,
                'person_name' => null,
                'diary_entries' => [
                    ['title' => 'Diseño del patrón Voronoi', 'content' => "Usé el plugin de Grasshopper para Rhino para generar el patrón Voronoi. Después de muchas pruebas, encontré los parámetros perfectos:\n\n- 50 puntos aleatorios\n- Offset interno de 2mm\n- Suavizado de bordes\n\nExporté el modelo a STL y lo importé en PrusaSlicer. El preview se ve espectacular.", 'type' => 'progress', 'time_spent' => 240, 'mood' => '🎨'],
                    ['title' => 'Prueba de translucidez', 'content' => "Imprimí varias muestras con diferentes espesores de pared para probar la translucidez:\n\n- 0.3mm: demasiado frágil\n- 0.4mm: perfecto, luz difusa bonita\n- 0.6mm: muy opaco\n\nMe quedo con 0.4mm. Usaré solo 1 perímetro y 0 capas sólidas superior/inferior.", 'type' => 'progress', 'time_spent' => 120, 'mood' => '💡'],
                    ['title' => 'ESP32 configurado', 'content' => "Flasheé WLED en el ESP32-C3. La configuración fue sencilla:\n\n1. Descargar el binario de WLED\n2. Flashear con esptool.py\n3. Conectar al punto de acceso\n4. Configurar WiFi de casa\n5. Añadir a Home Assistant\n\nYa puedo controlar la tira LED desde el móvil. Los efectos son muy bonitos.", 'type' => 'milestone', 'time_spent' => 90, 'mood' => '🔌'],
                ],
                'links' => [
                    ['title' => 'WLED Project', 'url' => 'https://kno.wled.ge/'],
                    ['title' => 'ESP32-C3 Datasheet', 'url' => 'https://www.espressif.com/esp32-c3'],
                    ['title' => 'Tutorial Voronoi en Grasshopper', 'url' => 'https://www.youtube.com/watch?v=voronoi-example'],
                ],
            ],
            [
                'title' => 'Mesita auxiliar estilo japonés',
                'description' => "## Inspiración\n\nMesa baja inspirada en el mobiliario tradicional japonés (chabudai). Diseño simple y funcional con líneas limpias.\n\n## Especificaciones\n\n- **Dimensiones**: 60 x 40 x 35 cm (alto)\n- **Material**: Pino macizo tratado\n- **Acabado**: Tinte nogal + cera\n- **Ensambles**: Espigas de madera, sin tornillos visibles\n\n## Técnicas utilizadas\n\n- Cortes de espiga con sierra de mano\n- Cajas con formón\n- Chaflanes a 45° en los bordes\n- Acabado tradicional con cera de abeja\n\n## Herramientas necesarias\n\n- Sierra japonesa ryoba\n- Formones de 6, 12 y 20mm\n- Cepillo de desbastar\n- Escuadra de carpintero\n- Gramil",
                'category' => 'carpentry',
                'status_slug' => 'in-progress',
                'priority' => 3,
                'started_at' => now()->subDays(21),
                'due_date' => now()->addDays(14),
                'is_public' => true,
                'person_name' => 'Pedro López',
                'diary_entries' => [
                    ['title' => 'Selección de madera', 'content' => "Fui al aserradero a buscar tablas de pino. Elegí 3 tablas de 200x20x3cm con la veta muy recta. Es importante que la madera esté bien seca (menos del 12% de humedad) para evitar que se deforme después.\n\nEl encargado del aserradero me ayudó a elegir tablas del mismo árbol para que el color sea uniforme.", 'type' => 'progress', 'time_spent' => 90, 'mood' => '🌲'],
                    ['title' => 'Corte de las piezas', 'content' => "Hoy corté todas las piezas:\n\n- 1 tablero de 60x40cm\n- 4 patas de 35cm\n- 4 travesaños de 50cm\n- 4 travesaños de 30cm\n\nUsé la sierra japonesa ryoba. El corte japonés (tirando) da mucho más control que el occidental (empujando).", 'type' => 'progress', 'time_spent' => 180, 'mood' => '🪚'],
                    ['title' => 'Espigas cortadas', 'content' => "Las espigas fueron lo más difícil. Hay que ser muy preciso: demasiado apretadas y la madera se raja, demasiado sueltas y la unión no es fuerte.\n\nHice primero pruebas en madera de desecho hasta coger el truco. Ahora las espigas entran con un ligero golpe de mazo.", 'type' => 'milestone', 'time_spent' => 300, 'mood' => '💪'],
                    ['title' => 'Problema: una pata torcida', 'content' => 'Al montar en seco descubrí que una pata está ligeramente torcida. Debe ser que la madera tenía tensiones internas. Tendré que cortar una pata nueva de la tabla sobrante.', 'type' => 'issue', 'time_spent' => 30, 'mood' => '😅'],
                ],
                'links' => [
                    ['title' => 'Japanese joinery - YouTube', 'url' => 'https://www.youtube.com/watch?v=japanese-joints'],
                    ['title' => 'Guía de uniones tradicionales', 'url' => 'https://www.popularwoodworking.com/japanese-joinery'],
                ],
            ],
            [
                'title' => 'Macetero autoriego modular',
                'description' => "## Descripción\n\nSistema de maceteros de autoriego apilables diseñados para impresión 3D. Cada módulo tiene su propio depósito de agua y sistema de mecha para suministrar agua a las raíces.\n\n## Características\n\n- Capacidad: 0.5L tierra + 0.2L agua\n- Sistema de mecha con algodón trenzado\n- Indicador de nivel de agua\n- Diseño modular: se apilan hasta 4 unidades\n- Drenaje incorporado\n\n## Materiales de impresión\n\n- PETG para resistencia a UV y humedad\n- Color: verde bosque\n- Relleno: 15% para ligereza\n\n## Plantas recomendadas\n\n- Pothos\n- Suculentas pequeñas\n- Hierbas aromáticas",
                'category' => '3d_printing',
                'status_slug' => 'completed',
                'priority' => 2,
                'started_at' => now()->subDays(30),
                'completed_at' => now()->subDays(5),
                'is_public' => true,
                'person_name' => null,
                'diary_entries' => [
                    ['title' => 'Diseño del sistema de mecha', 'content' => 'Investigué varios sistemas de autoriego. El más efectivo usa una mecha de algodón que sube el agua por capilaridad. Diseñé un canal interno que va desde el depósito hasta la zona de raíces.', 'type' => 'progress', 'time_spent' => 120, 'mood' => '💧'],
                    ['title' => 'Prototipo funcional', 'content' => "¡El prototipo funciona! Planté un pothos y después de una semana sin regar la tierra sigue húmeda. El sistema de mecha funciona perfectamente.\n\nSolo un ajuste: aumentar el diámetro del agujero de llenado para que sea más fácil añadir agua.", 'type' => 'milestone', 'time_spent' => 60, 'mood' => '🌱'],
                    ['title' => 'Versión final y documentación', 'content' => "Imprimí la versión final con todas las mejoras. También hice renders para subir a Printables y escribí las instrucciones de montaje.\n\nEl proyecto ya está publicado y tiene 50 likes en el primer día.", 'type' => 'milestone', 'time_spent' => 180, 'mood' => '🚀'],
                ],
                'links' => [
                    ['title' => 'Publicado en Printables', 'url' => 'https://www.printables.com/model/example'],
                    ['title' => 'Estudio sobre autoriego', 'url' => 'https://www.instructables.com/self-watering'],
                ],
            ],
        ];

        $count = 0;
        foreach ($projects as $projectData) {
            $personId = null;
            if (! empty($projectData['person_name']) && isset($people[$projectData['person_name']])) {
                $personId = $people[$projectData['person_name']];
            }

            $statusId = $statuses[$projectData['status_slug']] ?? $statuses['idea'] ?? 1;
            $categorySlug = str_replace('_', '-', $projectData['category'] ?? '');
            $categoryId = $categories[$categorySlug] ?? null;

            $project = Project::updateOrCreate(
                ['slug' => Str::slug($projectData['title']), 'user_id' => $user->id],
                [
                    'title' => $projectData['title'],
                    'slug' => Str::slug($projectData['title']),
                    'description' => $projectData['description'],
                    'category_id' => $categoryId,
                    'status_id' => $statusId,
                    'priority' => $projectData['priority'],
                    'due_date' => $projectData['due_date'] ?? null,
                    'started_at' => $projectData['started_at'] ?? null,
                    'completed_at' => $projectData['completed_at'] ?? null,
                    'person_id' => $personId,
                    'user_id' => $user->id,
                    'is_archived' => false,
                    'is_public' => $projectData['is_public'] ?? false,
                ]
            );

            // Entradas de diario
            if (! empty($projectData['diary_entries'])) {
                foreach ($projectData['diary_entries'] as $index => $entry) {
                    DiaryEntry::firstOrCreate(
                        [
                            'project_id' => $project->id,
                            'content' => $entry['content'],
                        ],
                        [
                            'project_id' => $project->id,
                            'title' => $entry['title'] ?? null,
                            'content' => $entry['content'],
                            'type' => $entry['type'],
                            'entry_date' => now()->subDays(count($projectData['diary_entries']) - $index),
                            'time_spent_minutes' => $entry['time_spent'],
                        ]
                    );
                }
            }

            // Enlaces
            if (! empty($projectData['links'])) {
                foreach ($projectData['links'] as $link) {
                    ProjectLink::firstOrCreate(
                        [
                            'project_id' => $project->id,
                            'url' => $link['url'],
                        ],
                        [
                            'project_id' => $project->id,
                            'title' => $link['title'],
                            'url' => $link['url'],
                        ]
                    );
                }
            }

            $count++;
        }

        $this->command->info('✓ '.$count.' proyectos de demo creados con entradas de diario y enlaces.');
    }
}
