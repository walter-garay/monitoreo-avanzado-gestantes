<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Sintoma;
use Illuminate\Support\Facades\DB;

class GestanteController extends Controller
{
    /**
     * Muestra la lista de gestantes.
     */
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        $query = User::where('rol', 'gestante');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('apellidos', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%'); // Opcional: buscar también en email
            });
        }

        $gestantes = $query->get();

        return Inertia::render('gestantes/Lista', [
            'gestantes' => $gestantes,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Muestra la vista de monitoreo de una gestante.
     */
    public function monitor(User $gestante): Response
    {
        $sintomas = $gestante->hasMany(Sintoma::class, 'gestante_id')
            ->orderByDesc('hora_inicio')
            ->get();

        // Últimos valores de signos vitales
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

        // Historial de signos vitales (últimos 30 registros)
        $historial_presion = DB::table('presion_arterials')
            ->where('gestante_id', $gestante->id)
            ->orderByDesc('hora_lectura')
            ->limit(30)
            ->get();
        $historial_temperatura = DB::table('temperatura_corporals')
            ->where('gestante_id', $gestante->id)
            ->orderByDesc('hora_lectura')
            ->limit(30)
            ->get();
        $historial_frecuencia = DB::table('frecuencia_cardiacas')
            ->where('gestante_id', $gestante->id)
            ->orderByDesc('hora_lectura')
            ->limit(30)
            ->get();

        return Inertia::render('gestantes/Monitor', [
            'gestante' => $gestante,
            'sintomas' => $sintomas,
            'ultima_presion' => $ultima_presion,
            'ultima_temperatura' => $ultima_temperatura,
            'ultima_frecuencia' => $ultima_frecuencia,
            'historial_presion' => $historial_presion,
            'historial_temperatura' => $historial_temperatura,
            'historial_frecuencia' => $historial_frecuencia,
        ]);
    }
}
