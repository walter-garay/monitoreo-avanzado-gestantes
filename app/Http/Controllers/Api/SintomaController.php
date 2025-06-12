<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Models\Sintoma;

class SintomaController extends Controller
{
    // Obtener todos los síntomas de la gestante autenticada
    public function index()
    {
        $sintomas = Sintoma::where('gestante_id', Auth::id())->latest()->get();
        return response()->json($sintomas);
    }

    // Registrar un nuevo síntoma
    public function store(Request $request)
    {
        $data = $request->validate([
            'hora_inicio' => 'required|date',
            'hora_fin' => 'nullable|date|after_or_equal:hora_inicio',
            'severidad' => 'required|in:leve,moderado,grave',
            'descripcion' => 'required|string',
        ]);

        $data['gestante_id'] = Auth::id();

        $sintoma = Sintoma::create($data);

        return response()->json($sintoma, 201);
    }

    // Mostrar un síntoma específico
    public function show(Sintoma $sintoma)
    {
        $this->authorizeSintoma($sintoma);

        return response()->json($sintoma);
    }

    // Actualizar un síntoma
    public function update(Request $request, Sintoma $sintoma)
    {
        $this->authorizeSintoma($sintoma);

        $data = $request->validate([
            'hora_inicio' => 'sometimes|required|date',
            'hora_fin' => 'nullable|date|after_or_equal:hora_inicio',
            'severidad' => 'sometimes|required|in:leve,moderado,grave',
            'descripcion' => 'sometimes|required|string',
        ]);

        $sintoma->update($data);

        return response()->json($sintoma);
    }

    // Eliminar un síntoma
    public function destroy(Sintoma $sintoma)
    {
        $this->authorizeSintoma($sintoma);

        $sintoma->delete();

        return response()->json(['message' => 'Síntoma eliminado']);
    }

    // Verifica que el síntoma pertenezca al usuario autenticado
    private function authorizeSintoma(Sintoma $sintoma)
    {
        if ($sintoma->gestante_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }
    }
}
