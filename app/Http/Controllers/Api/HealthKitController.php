<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class HealthKitController extends Controller
{
    public function receive(Request $request)
    {
        // 📌 Aquí procesas la notificación recibida
        // Por ejemplo, guardar en logs temporalmente
        Log::info('HealthKit notification received:', $request->all());

        // Respuesta obligatoria (200 OK)
        return response()->json([
            'status' => 'success',
            'message' => 'Notification received'
        ], 200);
    }}
