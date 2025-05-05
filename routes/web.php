<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\GestanteController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Grupo de rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Gestión de gestantes
    Route::resource('gestantes', GestanteController::class)->only(['index', 'show']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
