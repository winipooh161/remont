<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Partner\PartnerController;
use App\Http\Controllers\Partner\ProjectController;
use App\Http\Controllers\Partner\ProjectFileController;
use App\Http\Controllers\Partner\ProjectScheduleController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Маршруты для администраторов
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    // Другие маршруты администратора
});

// Маршруты для партнеров
Route::middleware(['auth', 'partner'])->prefix('partner')->group(function () {
    Route::get('/', [PartnerController::class, 'index'])->name('partner.dashboard');
    
    // Маршруты для управления объектами
    Route::resource('projects', ProjectController::class)->names([
        'index' => 'partner.projects.index',
        'create' => 'partner.projects.create',
        'store' => 'partner.projects.store',
        'show' => 'partner.projects.show',
        'edit' => 'partner.projects.edit',
        'update' => 'partner.projects.update',
        'destroy' => 'partner.projects.destroy',
    ]);
    
    // Маршруты для работы с файлами проектов
    Route::post('projects/{project}/files', [ProjectFileController::class, 'store'])
        ->name('partner.project-files.store');
    Route::get('project-files/{projectFile}/download', [ProjectFileController::class, 'download'])
        ->name('partner.project-files.download');
    Route::delete('project-files/{projectFile}', [ProjectFileController::class, 'destroy'])
        ->name('partner.project-files.destroy');
        
    // Маршруты для работы с графиком работ и материалов
    Route::prefix('projects/{project}/schedule')->group(function () {
        Route::get('items', [ProjectScheduleController::class, 'index'])->name('partner.projects.schedule.index');
        Route::post('items', [ProjectScheduleController::class, 'store'])->name('partner.projects.schedule.store');
        Route::put('items/positions', [ProjectScheduleController::class, 'updatePositions'])->name('partner.projects.schedule.positions');
        Route::get('export', [ProjectScheduleController::class, 'export'])->name('partner.projects.schedule.export');
    });
    
    Route::prefix('projects/schedule')->group(function () {
        Route::post('preview-excel', [ProjectScheduleController::class, 'previewExcel'])->name('partner.projects.schedule.preview-excel');
        Route::post('import-excel', [ProjectScheduleController::class, 'importExcel'])->name('partner.projects.schedule.import-excel');
    });
    
    Route::prefix('schedule-items')->group(function () {
        Route::get('{item}', [ProjectScheduleController::class, 'show'])->name('partner.schedule-items.show');
        Route::put('{item}', [ProjectScheduleController::class, 'update'])->name('partner.schedule-items.update');
        Route::delete('{item}', [ProjectScheduleController::class, 'destroy'])->name('partner.schedule-items.destroy');
    });
});

// Маршруты для профиля пользователя
Route::middleware(['auth'])->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('profile.update-password');
});
