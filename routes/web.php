<?php

declare(strict_types=1);

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiaryEntryController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\ProjectLinkController;
use App\Http\Controllers\PublicGalleryController;
use App\Http\Controllers\PublicProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// Public welcome page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public project view
Route::get('/p/{slug}', [PublicProjectController::class, 'show'])->name('public.project.show');
Route::get('/p/{slug}/pdf', [PublicProjectController::class, 'pdf'])->name('public.project.pdf');
Route::get('/p/{slug}/zip', [PublicProjectController::class, 'zip'])->name('public.project.zip');

// Public gallery
Route::get('/g/{user}', [PublicGalleryController::class, 'show'])->name('public.gallery');

// Protected routes (require authentication)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{project}/archive', [ProjectController::class, 'archive'])->name('projects.archive');
    Route::post('projects/{project}/unarchive', [ProjectController::class, 'unarchive'])->name('projects.unarchive');

    // Project Files
    Route::prefix('projects/{project}/files')->name('projects.files.')->group(function () {
        Route::get('/', [ProjectFileController::class, 'index'])->name('index');
        Route::post('/', [ProjectFileController::class, 'store'])->name('store');
        Route::get('{file}', [ProjectFileController::class, 'show'])->name('show');
        Route::get('{file}/download', [ProjectFileController::class, 'download'])->name('download');
        Route::delete('{file}', [ProjectFileController::class, 'destroy'])->name('destroy');
        Route::post('bulk-delete', [ProjectFileController::class, 'bulkDelete'])->name('bulk-delete');
    });

    // Diary Entries
    Route::prefix('projects/{project}/diary')->name('projects.diary.')->group(function () {
        Route::get('/', [DiaryEntryController::class, 'index'])->name('index');
        Route::post('/', [DiaryEntryController::class, 'store'])->name('store');
        Route::get('{entry}', [DiaryEntryController::class, 'show'])->name('show');
        Route::put('{entry}', [DiaryEntryController::class, 'update'])->name('update');
        Route::delete('{entry}', [DiaryEntryController::class, 'destroy'])->name('destroy');
    });

    // Project Links
    Route::prefix('projects/{project}/links')->name('projects.links.')->group(function () {
        Route::get('/', [ProjectLinkController::class, 'index'])->name('index');
        Route::post('/', [ProjectLinkController::class, 'store'])->name('store');
        Route::put('{link}', [ProjectLinkController::class, 'update'])->name('update');
        Route::delete('{link}', [ProjectLinkController::class, 'destroy'])->name('destroy');
        Route::post('reorder', [ProjectLinkController::class, 'reorder'])->name('reorder');
    });

    // People
    Route::resource('people', PersonController::class);

    // Tags
    Route::resource('tags', TagController::class)->only(['index', 'store', 'update', 'destroy']);

    // Calendar
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('index');
        Route::get('upcoming', [CalendarController::class, 'upcoming'])->name('upcoming');
        Route::post('events', [CalendarController::class, 'store'])->name('events.store');
        Route::get('events/{event}', [CalendarController::class, 'show'])->name('events.show');
        Route::put('events/{event}', [CalendarController::class, 'update'])->name('events.update');
        Route::delete('events/{event}', [CalendarController::class, 'destroy'])->name('events.destroy');
    });
});
