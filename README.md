# 🛠️ Build Diary

Aplicación de gestión de proyectos DIY para makers, carpinteros, impresores 3D y artesanos. Documenta tus proyectos, controla presupuestos, gestiona inventario y comparte tu trabajo.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-3.3-FDAE4B?style=flat-square)
![Tailwind](https://img.shields.io/badge/Tailwind-4-06B6D4?style=flat-square&logo=tailwindcss)
![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=flat-square&logo=php)

## ✨ Características

### 📁 Gestión de Proyectos
- Estados personalizables con colores
- Categorías (carpintería, impresión 3D, electrónica, etc.)
- Prioridades (baja, media, alta)
- Fechas de inicio, límite y finalización
- Proyectos públicos con URL compartible
- Exportación a PDF profesional
- Import/Export completo en ZIP

### 📔 Diario de Proyecto
- Entradas con tipos: nota, progreso, hito, problema, solución
- Registro de tiempo dedicado
- Soporte Markdown con vista previa

### 💰 Control de Presupuesto
- Materiales, herramientas, consumibles, servicios
- Seguimiento de compras realizadas vs pendientes
- Totales automáticos en tiempo real
- Proveedores y enlaces de compra

### 📷 Galería y Archivos
- Imágenes con galería visual
- Documentos, modelos 3D y adjuntos
- Descarga ZIP con todos los archivos

### 📦 Inventario
- Gestión de herramientas y materiales
- Control de stock con alertas de mínimos
- Historial de préstamos

### 📅 Calendario
- Eventos automáticos desde fechas de proyecto
- Cumpleaños de personas con recordatorios
- Eventos personalizados

### 👥 Personas
- Asociar personas a proyectos ("regalo para...")
- Recordatorios de cumpleaños

### 🏷️ Organización
- Etiquetas con colores personalizados
- Listas de tareas por proyecto
- Enlaces útiles

## 🚀 Instalación

### Requisitos
- Docker & Docker Compose
- PHP 8.4+ (solo para composer inicial)

### Pasos

```bash
# Clonar repositorio
git clone https://github.com/TU_USUARIO/build-diary.git
cd build-diary

# Instalar dependencias
composer install

# Configuración
cp .env.example .env

# Iniciar contenedores
./vendor/bin/sail up -d

# Setup inicial
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail artisan storage:link

# Assets frontend
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### Acceso
- **Panel admin**: http://localhost/admin
- **Usuario demo**: `admin@builddiary.test`
- **Contraseña**: `password`

## 🧪 Testing

```bash
# Tests
./vendor/bin/sail artisan test

# O con Pest directamente
./vendor/bin/pest

# Análisis estático
./vendor/bin/phpstan

# Formateo de código
./vendor/bin/pint
```

## 📦 Stack Tecnológico

| Tecnología | Versión | Uso |
|------------|---------|-----|
| Laravel | 12 | Framework backend |
| FilamentPHP | 3.3 | Panel de administración |
| Tailwind CSS | 4 | Estilos frontend |
| MySQL | 8 | Base de datos |
| Redis | 7 | Cache y colas |
| Laravel Sail | - | Entorno Docker |

## 📂 Estructura

```
app/
├── Data/              # DTOs con Spatie Data
├── Filament/          # Recursos y páginas del panel
│   ├── Resources/     # CRUD de entidades
│   └── Pages/         # Páginas personalizadas
├── Http/Controllers/  # Controladores (públicos)
├── Models/            # Modelos Eloquent
├── Observers/         # Observers (ej: ProjectObserver)
├── Policies/          # Autorización
└── Services/          # Lógica de negocio
    ├── Calendar/
    ├── Diary/
    ├── Project/
    └── ...
```

## 🔧 Comandos Útiles

```bash
# Regenerar base de datos con datos demo
sail artisan migrate:fresh --seed

# Solo datos demo (sin resetear)
sail artisan db:seed --class=DemoSeeder

# Limpiar cache
sail artisan optimize:clear

# Generar IDE helpers
sail artisan ide-helper:generate
sail artisan ide-helper:models -N
```

## 📝 Licencia

MIT License - Ver [LICENSE](LICENSE) para más detalles.

---

Desarrollado con ❤️ para la comunidad maker
