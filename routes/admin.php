<?php

use App\Admin\ModuleRegistry;
use App\Http\Controllers\Admin\CrudController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SystemController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::get('profile', [ProfileController::class, 'show'])->name('profile');
Route::post('notifications/read', [ProfileController::class, 'readNotifications'])->name('notifications.read');

Route::get('leads/export', [LeadController::class, 'export'])->name('leads.export');
Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
Route::get('leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
Route::patch('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
Route::post('leads/{lead}/notes', [LeadController::class, 'note'])->name('leads.notes.store');
Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

Route::get('activity', [ReportController::class, 'activity'])->name('activity');
Route::get('seo-report', [ReportController::class, 'seo'])->name('seo-report');

Route::get('system', [SystemController::class, 'index'])->name('system.index');
Route::post('system/migrate', [SystemController::class, 'migrate'])->name('system.migrate');
Route::post('system/clear-cache', [SystemController::class, 'clearCache'])->name('system.clear-cache');
Route::post('system/test-mail', [SystemController::class, 'testMail'])->name('system.test-mail');
Route::post('system/tasks/{task}/retry', [SystemController::class, 'retryTask'])->name('system.tasks.retry');
Route::delete('system/tasks/{task}', [SystemController::class, 'forgetTask'])->name('system.tasks.forget');

/*
| Every content module (config/admin.php) is served by one controller.
*/
Route::prefix('{module}')->where(['module' => implode('|', array_map('preg_quote', app(ModuleRegistry::class)->keys()))])
    ->name('crud.')->group(function (): void {
        Route::get('/', [CrudController::class, 'index'])->name('index');
        Route::get('create', [CrudController::class, 'create'])->name('create');
        Route::post('/', [CrudController::class, 'store'])->name('store');
        Route::post('reorder', [CrudController::class, 'reorder'])->name('reorder');
        Route::get('{record}/edit', [CrudController::class, 'edit'])->whereNumber('record')->name('edit');
        // POST so that file uploads work (multipart forms cannot use PUT).
        Route::post('{record}', [CrudController::class, 'update'])->whereNumber('record')->name('update');
        Route::delete('{record}', [CrudController::class, 'destroy'])->whereNumber('record')->name('destroy');
        Route::post('{record}/restore', [CrudController::class, 'restore'])->whereNumber('record')->name('restore');
    });
