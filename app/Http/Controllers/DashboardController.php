<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sintoma;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $gestantes = User::where('rol', 'gestante')->get();

        $resumen = $gestantes->map(function ($gestante) {
            $ultimos_sintomas = Sintoma::where('gestante_id', $gestante->id)
                ->orderByDesc('hora_inicio')
                ->limit(3)
                ->get();

            $ultima_presion = DB::table('presion_arterials')
                ->where('gestante_id', $gestante->id)
                ->orderByDesc('hora_lectura')
                ->first();
            $ultima_temperatura = DB::table('temperatura_corporals')
                ->where('gestante_id', $gestante->id)
                ->orderByDesc('hora_lectura')
                ->first();
            $ultima_frecuencia = DB::table('frecuencia_cardiacas')
                ->where('gestante_id', $gestante->id)
                ->orderByDesc('hora_lectura')
                ->first();

            return [
                'id' => $gestante->id,
                'nombre' => $gestante->name,
                'apellidos' => $gestante->apellidos,
                'ultimos_sintomas' => $ultimos_sintomas,
                'ultima_presion' => $ultima_presion,
                'ultima_temperatura' => $ultima_temperatura,
                'ultima_frecuencia' => $ultima_frecuencia,
            ];
        });

        return Inertia::render('Dashboard', [
            'resumen_gestantes' => $resumen,
        ]);
    }
}
