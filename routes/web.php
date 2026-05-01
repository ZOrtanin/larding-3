<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Тестовый маршрут Laravel по умолчанию.
Route::get('/index', function () {
    return view('welcome');
});

Route::middleware('not_installed')->group(function (): void {
    Route::get('/install', [InstallController::class, 'index'])->name('install.index');
    Route::get('/install/database', [InstallController::class, 'database'])->name('install.database');
    Route::post('/install/database', [InstallController::class, 'storeDatabase'])->name('install.database.store');
    Route::get('/install/initialize', [InstallController::class, 'initialize'])->name('install.initialize');
    Route::post('/install/initialize', [InstallController::class, 'runInitialization'])->name('install.initialize.run');
    Route::get('/install/admin', [InstallController::class, 'admin'])->name('install.admin');
    Route::post('/install/admin', [InstallController::class, 'storeAdmin'])->name('install.admin.store');
});

// Публичная главная страница сайта.
Route::get('/', [IndexController::class, 'index'])->name('template.index');

// Публичная отправка лид-формы с ограничением по частоте.
Route::post('/leads', [LeadController::class, 'store'])
    ->middleware('throttle:lead-form')
    ->name('leads.store');

// Админ-панель доступна только авторизованным пользователям с назначенной ролью.
Route::middleware(['auth', 'verified', 'role.assigned'])->group(function () {
    // Главные страницы админ-панели.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/lids', [LeadController::class, 'index'])->name('lids');
    Route::get('/files', [FileController::class, 'index'])->name('files');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

    // Работа с лидами и статистикой.
    Route::patch('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.status');
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.delete');
    Route::delete('/statistics', [StatisticsController::class, 'destroy'])->name('statistics.delete');

    // Управление файлами.
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.delete');

    // Центр уведомлений.
    Route::get('/notifications/{notification}', [NotificationController::class, 'open'])->name('notifications.open');
    Route::patch('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read_all');

    // Общие настройки сайта.
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/update-cms', [SettingsController::class, 'updateCms'])->name('settings.update-cms');
    Route::post('/settings/test-mail', [SettingsController::class, 'sendTestMail'])->name('settings.test-mail');

    // Создание и редактирование блоков сайта.
    Route::patch('/create', [BlockController::class, 'create'])->name('settings.create');
    Route::get('/block-templates', [BlockController::class, 'listTemplates'])->name('settings.templates.index');
    Route::get('/block-templates/{blockTemplate}', [BlockController::class, 'showTemplate'])->name('settings.templates.show');
    Route::get('/blocks/{block}', [BlockController::class, 'show'])->name('settings.blocks.show');
    Route::patch('/blocks/{block}', [BlockController::class, 'update'])->name('settings.blocks.update');
    Route::delete('/blocks/{block}', [BlockController::class, 'delete'])->name('settings.blocks.delete');
    Route::patch('/block/up/{block}', [BlockController::class, 'moveUp'])->name('settings.blocks.up');
    Route::patch('/block/down/{block}', [BlockController::class, 'moveDown'])->name('settings.blocks.down');
    Route::patch('/block/visibility/{block}', [BlockController::class, 'toggleVisibility'])->name('settings.blocks.visibility');
});

// Управление пользователями доступно только супер-администратору.
Route::middleware(['auth', 'verified', 'role.assigned', 'role:super_admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
    Route::patch('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.password');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.delete');
});

// Профиль авторизованного пользователя.
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/token', [ProfileController::class, 'refreshToken'])->name('profile.token');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
