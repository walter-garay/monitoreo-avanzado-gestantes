<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CentroSalud;

class CentroSaludSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $centros = [
            [
                'nombre' => 'Centro de Salud Aparicio Pomares',
                'direccion' => 'Jr. San Martín 123, Huánuco',
                'telefono' => '(062) 512345', 
            ],
            [
                'nombre' => 'Centro de Salud Pillco Marca',
                'direccion' => 'Av. Los Laureles 456, Pillco Marca',
                'telefono' => '(062) 516789',
            ],
            [
                'nombre' => 'Puesto de Salud Amarilis',
                'direccion' => 'Jr. Túpac Amaru 789, Amarilis',
                'telefono' => '(062) 519876',
            ],
            [
                'nombre' => 'Centro de Salud Santa María del Valle',
                'direccion' => 'Carretera Central Km 5, Santa María del Valle',
                'telefono' => '(062) 515678',
            ],
            [
                'nombre' => 'Puesto de Salud Churubamba',
                'direccion' => 'Plaza Principal s/n, Churubamba',
                'telefono' => '(062) 514321',
            ],
        ];

        foreach ($centros as $centro) {
            CentroSalud::create($centro);
        }
    }
}
