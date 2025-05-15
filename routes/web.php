<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Partner\PartnerController;
use App\Http\Controllers\Partner\ProjectController;
use App\Http\Controllers\Partner\ProjectFileController;
use App\Http\Controllers\Partner\ProjectScheduleController;
use App\Http\Controllers\Partner\ProjectFinanceController;
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
    
    // ИСПРАВЛЕНО: Удалено дублирование маршрутов finance
    Route::prefix('projects/{project}/finance')->group(function () {
        Route::get('/', [ProjectFinanceController::class, 'index'])->name('partner.projects.finance.index');
        Route::post('/items', [ProjectFinanceController::class, 'store'])->name('partner.projects.finance.store');
        Route::put('/positions', [ProjectFinanceController::class, 'updatePositions'])->name('partner.projects.finance.positions');
        Route::get('/export', [ProjectFinanceController::class, 'export'])->name('partner.projects.finance.export');
    });
    
    // Маршруты для работы с отдельными элементами финансов
    Route::prefix('finance-items')->group(function () {
        Route::get('{item}', [ProjectFinanceController::class, 'show'])
            ->name('partner.finance-items.show')
            ->where('item', '[0-9]+')
            ->missing(function () {
                return response()->json(['success' => false, 'message' => 'Элемент не найден'], 404);
            });
        Route::put('{item}', [ProjectFinanceController::class, 'update'])
            ->name('partner.finance-items.update')
            ->where('item', '[0-9]+');
        Route::delete('{item}', [ProjectFinanceController::class, 'destroy'])
            ->name('partner.finance-items.destroy')
            ->where('item', '[0-9]+');
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
