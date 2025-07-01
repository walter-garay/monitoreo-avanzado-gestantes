<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class TemperaturaCorporalSeeder extends Seeder
{
    public function run(): void
    {
        $gestantes = User::where('rol', 'gestante')->get();
        foreach ($gestantes as $gestante) {
            for ($i = 0; $i < 50; $i++) {
                DB::table('temperatura_corporals')->insert([
                    'gestante_id' => $gestante->id,
                    'valor' => rand(360, 380) / 10, // 36.0 - 38.0 °C
                    'hora_lectura' => Carbon::now()->subDays(rand(0, 100))->addMinutes(rand(0, 1440)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
