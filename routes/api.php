<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GestanteController;
use App\Http\Controllers\Api\SintomaController;
use App\Http\Controllers\Api\ContenidoEducativoController;
use App\Http\Controllers\Api\SignoVitalController;
use App\Http\Controllers\Api\AlertaController;
use App\Http\Controllers\Api\NotificacionController;

Route::prefix('v1')->group(function () {

    // Autenticación (para app móvil)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas protegidas con Sanctum
    Route::middleware('auth:sanctum')->group(function () {

        // Perfil del usuario autenticado
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Gestantes
        Route::get('/gestantes', [GestanteController::class, 'index']);
        Route::get('/gestantes/{id}', [GestanteController::class, 'show']);
        Route::put('/gestantes/{id}', [GestanteController::class, 'update']);

        // Síntomas (ingresados por la gestante)
        Route::post('/gestantes/{id}/sintomas', [SintomaController::class, 'store']);
        Route::get('/gestantes/{id}/sintomas', [SintomaController::class, 'index']);

        // Signos vitales recibidos del wearable
        Route::post('/gestantes/{id}/signos-vitales', [SignoVitalController::class, 'store']);
        Route::get('/gestantes/{id}/signos-vitales', [SignoVitalController::class, 'index']);

        // Contenido educativo (solo para la app)
        Route::get('/educacion', [ContenidoEducativoController::class, 'index']);

        // Alertas generadas
        Route::get('/gestantes/{id}/alertas', [AlertaController::class, 'index']);

        // Notificaciones
        Route::get('/gestantes/{id}/notificaciones', [NotificacionController::class, 'index']);
    });
});
