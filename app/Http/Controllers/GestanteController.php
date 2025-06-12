<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

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

        return Inertia::render('gestantes/Index', [
            'gestantes' => $gestantes,
            'filters' => $request->only(['search']),
        ]);
    }
}
