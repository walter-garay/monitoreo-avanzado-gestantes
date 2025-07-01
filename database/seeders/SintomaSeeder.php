<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class SintomaSeeder extends Seeder
{
    public function run(): void
    {
        $descripciones = [
            'Dolor de cabeza',
            'Náuseas',
            'Mareos',
            'Fatiga',
            'Dolor abdominal',
            'Vómitos',
            'Hinchazón',
            'Calambres',
            'Sangrado leve',
            'Dificultad para respirar',
            'Palpitaciones',
            'Dolor lumbar',
            'Insomnio',
            'Ansiedad',
            'Fiebre',
        ];
        $severidades = ['leve', 'moderado', 'grave'];

        $gestantes = User::where('rol', 'gestante')->get();
        foreach ($gestantes as $gestante) {
            $cantidad = rand(20, 50);
            for ($i = 0; $i < $cantidad; $i++) {
                $inicio = Carbon::now()->subDays(rand(0, 100))->addMinutes(rand(0, 1440));
                $fin = (rand(0, 1) === 1) ? (clone $inicio)->addMinutes(rand(10, 180)) : null;
                DB::table('sintomas')->insert([
                    'gestante_id' => $gestante->id,
                    'descripcion' => $descripciones[array_rand($descripciones)],
                    'severidad' => $severidades[array_rand($severidades)],
                    'hora_inicio' => $inicio,
                    'hora_fin' => $fin,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
