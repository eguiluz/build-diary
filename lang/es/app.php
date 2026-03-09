<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Language Lines
    |--------------------------------------------------------------------------
    */

    'app_name' => 'Build Diary',

    // Navigation
    'navigation' => [
        'projects' => 'Proyectos',
        'people' => 'Personas',
        'tags' => 'Etiquetas',
        'calendar' => 'Calendario',
        'statuses' => 'Estados',
        'settings' => 'Configuración',
    ],

    // Projects
    'project' => [
        'label' => 'Proyecto',
        'plural' => 'Proyectos',
        'title' => 'Título',
        'slug' => 'Slug',
        'description' => 'Descripción',
        'category' => 'Categoría',
        'status' => 'Estado',
        'priority' => 'Prioridad',
        'due_date' => 'Fecha límite',
        'started_at' => 'Fecha de inicio',
        'completed_at' => 'Fecha de finalización',
        'is_archived' => 'Archivado',
        'metadata' => 'Metadatos',
        'person' => 'Persona asociada',
        'user' => 'Usuario',
        'categories' => [
            'carpentry' => 'Carpintería',
            '3d_printing' => 'Impresión 3D',
            'paper_art' => 'Arte en papel',
            'electronics' => 'Electrónica',
            'sewing' => 'Costura',
            'crafts' => 'Manualidades',
            'other' => 'Otro',
        ],
    ],

    // Project Files
    'project_file' => [
        'label' => 'Archivo',
        'plural' => 'Archivos',
        'filename' => 'Nombre del archivo',
        'path' => 'Ruta',
        'description' => 'Descripción',
        'mime_type' => 'Tipo',
        'size' => 'Tamaño',
        'uploaded_at' => 'Subido',
    ],

    // Diary Entries
    'diary_entry' => [
        'label' => 'Entrada de diario',
        'plural' => 'Entradas de diario',
        'title' => 'Título',
        'content' => 'Contenido',
        'type' => 'Tipo',
        'entry_date' => 'Fecha',
        'time_spent' => 'Tiempo',
        'time_spent_minutes' => 'Tiempo (minutos)',
        'types' => [
            'note' => 'Nota',
            'progress' => 'Progreso',
            'milestone' => 'Hito',
            'issue' => 'Problema',
            'solution' => 'Solución',
        ],
    ],

    // Project Links
    'project_link' => [
        'label' => 'Enlace',
        'plural' => 'Enlaces',
        'title' => 'Título',
        'url' => 'URL',
        'description' => 'Descripción',
    ],

    // People
    'person' => [
        'label' => 'Persona',
        'plural' => 'Personas',
        'name' => 'Nombre',
        'email' => 'Correo electrónico',
        'phone' => 'Teléfono',
        'birthday' => 'Cumpleaños',
        'notes' => 'Notas',
        'relationship' => 'Relación',
        'relationships' => [
            'family' => 'Familia',
            'friend' => 'Amigo',
            'colleague' => 'Colega',
            'client' => 'Cliente',
            'other' => 'Otro',
        ],
    ],

    // Tags
    'tag' => [
        'label' => 'Etiqueta',
        'plural' => 'Etiquetas',
        'name' => 'Nombre',
        'slug' => 'Slug',
        'color' => 'Color',
        'description' => 'Descripción',
    ],

    // Project Statuses
    'project_status' => [
        'label' => 'Estado del proyecto',
        'plural' => 'Estados de proyecto',
        'name' => 'Nombre',
        'slug' => 'Slug',
        'color' => 'Color',
        'order' => 'Orden',
        'is_default' => 'Por defecto',
        'is_completed' => 'Completado',
    ],

    // Calendar Events
    'calendar_event' => [
        'label' => 'Evento',
        'plural' => 'Eventos',
        'title' => 'Título',
        'description' => 'Descripción',
        'type' => 'Tipo',
        'event_date' => 'Fecha',
        'event_time' => 'Hora',
        'end_date' => 'Fecha de fin',
        'is_all_day' => 'Todo el día',
        'is_recurring' => 'Recurrente',
        'color' => 'Color',
        'reminder_enabled' => 'Recordatorio',
        'reminder_minutes_before' => 'Minutos antes',
        'types' => [
            'deadline' => 'Fecha límite',
            'birthday' => 'Cumpleaños',
            'custom' => 'Personalizado',
            'reminder' => 'Recordatorio',
        ],
    ],

    // Dashboard
    'dashboard' => [
        'title' => 'Panel de control',
        'stats' => [
            'active_projects' => 'Proyectos activos',
            'total_projects' => 'Proyectos totales',
            'people' => 'Personas',
            'diary_entries' => 'Entradas de diario',
            'upcoming_events' => 'Eventos próximos',
            'hours_worked' => 'Horas trabajadas',
            'in_progress' => 'En progreso',
            'all_projects' => 'Todos los proyectos',
            'registered_contacts' => 'Contactos registrados',
            'total_entries' => 'Total de registros',
            'next_7_days' => 'Próximos 7 días',
            'total_logged' => 'Total registrado',
        ],
        'recent_projects' => 'Proyectos recientes',
        'upcoming_birthdays' => 'Próximos cumpleaños',
    ],

    // Common
    'common' => [
        'created_at' => 'Creado',
        'updated_at' => 'Actualizado',
        'deleted_at' => 'Eliminado',
        'actions' => 'Acciones',
        'view' => 'Ver',
        'edit' => 'Editar',
        'delete' => 'Eliminar',
        'create' => 'Crear',
        'save' => 'Guardar',
        'cancel' => 'Cancelar',
        'search' => 'Buscar',
        'filter' => 'Filtrar',
        'yes' => 'Sí',
        'no' => 'No',
    ],

    // Notifications
    'notifications' => [
        'event_reminder' => [
            'subject' => 'Recordatorio: :title',
            'greeting' => '¡Hola!',
            'line1' => 'Te recordamos que tienes el evento: :title',
            'line2' => 'Fecha: :date - Hora: :time',
            'action' => 'Ver calendario',
            'salutation' => '¡Que tengas un buen día!',
        ],
        'birthday_reminder' => [
            'subject' => 'Cumpleaños de :name próximo',
            'greeting' => '¡Hola!',
            'line1' => 'El cumpleaños de :name es :days',
            'line2' => 'Fecha: :date (cumplirá :age años)',
            'action' => 'Ver contacto',
            'salutation' => '¡No olvides felicitarle!',
            'today' => 'hoy',
            'in_days' => 'en :days días',
        ],
    ],

    // Preferences page
    'preferences' => 'Preferencias',
    'preferences_page' => [
        'title' => 'Preferencias',
        'navigation_group' => 'Configuración',
        'appearance' => 'Apariencia',
        'appearance_description' => 'Personaliza el aspecto visual del panel',
        'theme' => 'Tema',
        'theme_light' => 'Siempre usar tema claro',
        'theme_dark' => 'Siempre usar tema oscuro',
        'theme_system' => 'Usar la preferencia de tu sistema operativo',
        'sidebar_collapsed' => 'Barra lateral colapsada por defecto',
        'sidebar_collapsed_helper' => 'Iniciar con el menú lateral minimizado',
        'projects_section' => 'Proyectos',
        'projects_section_description' => 'Configuración de la vista de proyectos',
        'projects_per_page' => 'Proyectos por página',
        'show_completed_tasks' => 'Mostrar tareas completadas',
        'show_completed_tasks_helper' => 'Mostrar las tareas completadas en las listas de tareas',
        'notifications_section' => 'Notificaciones',
        'notifications_section_description' => 'Gestiona tus preferencias de notificaciones',
        'email_notifications' => 'Notificaciones por email',
        'email_notifications_helper' => 'Recibir recordatorios de eventos y cumpleaños por email',
    ],
    'language' => 'Idioma',
    'language_description' => 'Selecciona tu idioma preferido para la interfaz',
    'preferences_saved' => 'Preferencias guardadas',

    // Public views
    'public' => [
        'view_more_projects' => '← Ver más proyectos',
        'priority_low' => 'Prioridad baja',
        'priority_medium' => 'Prioridad media',
        'priority_high' => 'Prioridad alta',
        'started' => 'Iniciado',
        'due_date' => 'Fecha límite',
        'completed' => 'Completado',
        'dedicated_to' => 'Dedicado a',
        'for' => 'para',
        'checklist' => 'Checklist',
        'tasks' => 'tareas',
        'percent_completed' => ':percent% completado',
        'all_completed' => '¡Completado!',
        'budget' => 'Presupuesto',
        'total' => 'Total',
        'spent' => 'Gastado',
        'pending' => 'Pendiente',
        'budget_percent_spent' => ':percent% del presupuesto gastado',
        'links' => 'Enlaces',
        'downloadable_files' => 'Archivos descargables',
        'download' => 'Descargar',
        'project_diary' => 'Diario del proyecto',
        'time_dedicated' => ':hours h :minutes m dedicados',
        'published_with' => 'Publicado con Build Diary',
        'expense_categories' => [
            'material' => 'Materiales',
            'tool' => 'Herramientas',
            'consumable' => 'Consumibles',
            'service' => 'Servicios',
            'other' => 'Otros',
        ],
        'entry_types' => [
            'progress' => 'Progreso',
            'issue' => 'Problema',
            'solution' => 'Solución',
            'milestone' => 'Hito',
            'note' => 'Nota',
        ],
        'image_gallery' => 'Galería de imágenes',
        'tasks_completed' => ':completed / :total tareas completadas',
        'spending_progress' => 'Progreso del gasto',
        'generated_on' => 'Generado el :date',
        'scan_to_view_online' => 'Escanea para ver online',
    ],

];
