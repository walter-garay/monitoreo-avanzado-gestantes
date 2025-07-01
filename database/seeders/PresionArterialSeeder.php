<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class PresionArterialSeeder extends Seeder
{
    public function run(): void
    {
        $gestantes = User::where('rol', 'gestante')->get();
        foreach ($gestantes as $gestante) {
            for ($i = 0; $i < 50; $i++) {
                DB::table('presion_arterials')->insert([
                    'gestante_id' => $gestante->id,
                    'valor_sistolica' => rand(100, 140),
                    'valor_diastolica' => rand(60, 90),
                    'hora_lectura' => Carbon::now()->subDays(rand(0, 100))->addMinutes(rand(0, 1440)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
