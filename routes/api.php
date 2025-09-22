<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SintomaController;
use App\Http\Controllers\Api\ContenidoEducativoController;
use App\Http\Controllers\Api\SignoVitalController;
use App\Http\Controllers\Api\AlertaController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Api\FirebaseController;
use App\Http\Controllers\Api\HealthKitController;


Route::prefix('v1')->group(function () {

    // Autenticación (para app móvil)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // ✅ Endpoint público para recibir notificaciones de HealthKit
    Route::post('/healthkit/notifications', [HealthKitController::class, 'receive']);


    // Rutas protegidas con Sanctum
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        // Perfil del usuario autenticado
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Síntomas del usuario auth (gestante)
        Route::get('/sintomas', [SintomaController::class, 'index']);
        Route::post('/sintomas', [SintomaController::class, 'store']);

        // Signos vitales recibidos del wearable
        Route::post('/gestantes/{id}/signos-vitales', [SignoVitalController::class, 'store']);
        Route::get('/gestantes/{id}/signos-vitales', [SignoVitalController::class, 'index']);

        // Contenido educativo (solo para la app)
        // Route::get('/educacion', [ContenidoEducativoController::class, 'index']);

        // Alertas generadas
        Route::get('/gestantes/{id}/alertas', [AlertaController::class, 'index']);

        // Notificaciones
        Route::get('/gestantes/{id}/notificaciones', [NotificacionController::class, 'index']);
        Route::post('/fcm/token', [FirebaseController::class, 'storeToken']);


    });
});
