<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class FrecuenciaCardiacaSeeder extends Seeder
{
    public function run(): void
    {
        $gestantes = User::where('rol', 'gestante')->get();
        foreach ($gestantes as $gestante) {
            for ($i = 0; $i < 50; $i++) {
                DB::table('frecuencia_cardiacas')->insert([
                    'gestante_id' => $gestante->id,
                    'valor' => rand(60, 110), // frecuencia cardiaca típica
                    'hora_lectura' => Carbon::now()->subDays(rand(0, 100))->addMinutes(rand(0, 1440)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
