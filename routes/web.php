<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FirebaseController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GestanteController;

Route::get('/', function () {
    // return Inertia::render('Welcome');
    return redirect()->route('dashboard');
})->name('home');

// Grupo de rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Gestión de gestantes
    Route::resource('gestantes', GestanteController::class)->only(['index', 'show']);

    // Monitoreo de gestante
    Route::get('/monitor/{gestante}', [GestanteController::class, 'monitor'])->name('gestantes.monitor');

    // Notificación manual a gestante
    Route::post('/gestantes/{id}/notificar', [FirebaseController::class, 'notificar']);

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
