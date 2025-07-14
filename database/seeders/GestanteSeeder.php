<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class GestanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gestantes = [
            [
                'name' => 'Scoly',
                'apellidos' => 'Del Castillo Pérez',
                'fecha_nacimiento' => '1995-03-10',
                'fecha_inicio_gestacion' => '2023-01-15',
                'peso_kg' => 60.5,
                'altura_cm' => 165.0,
                'rol' => 'gestante',
                'email' => 'coly@gmail.com',
                'password' => Hash::make('coly@gmail.com'),
                'centro_id' => 1, // Asigna un ID de centro existente si tienes centros de salud
            ],
            [
                'name' => 'María',
                'apellidos' => 'López Fernández',
                'fecha_nacimiento' => '1998-07-22',
                'fecha_inicio_gestacion' => '2023-02-20',
                'peso_kg' => 58.0,
                'altura_cm' => 160.0,
                'rol' => 'gestante',
                'email' => 'maria.lopez@example.com',
                'password' => Hash::make('12345678'),
                'centro_id' => 2, // Asigna un ID de centro existente
            ],
            [
                'name' => 'Sofía',
                'apellidos' => 'Martínez Ruiz',
                'fecha_nacimiento' => '1993-11-05',
                'fecha_inicio_gestacion' => '2023-03-10',
                'peso_kg' => 62.3,
                'altura_cm' => 170.0,
                'rol' => 'gestante',
                'email' => 'sofia.martinez@example.com',
                'password' => Hash::make('12345678'),
                'centro_id' => 1, // Asigna un ID de centro existente
            ],
            [
                'name' => 'Laura',
                'apellidos' => 'Sánchez González',
                'fecha_nacimiento' => '1996-01-30',
                'fecha_inicio_gestacion' => '2023-04-01',
                'peso_kg' => 55.8,
                'altura_cm' => 158.0,
                'rol' => 'gestante',
                'email' => 'laura.sanchez@example.com',
                'password' => Hash::make('12345678'),
                'centro_id' => 3, // Asigna un ID de centro existente
            ],
            [
                'name' => 'Isabella',
                'apellidos' => 'Rodríguez Díaz',
                'fecha_nacimiento' => '1994-09-18',
                'fecha_inicio_gestacion' => '2023-05-05',
                'peso_kg' => 65.1,
                'altura_cm' => 172.0,
                'rol' => 'gestante',
                'email' => 'isabella.rodriguez@example.com',
                'password' => Hash::make('12345678'),
                'centro_id' => 2, // Asigna un ID de centro existente
            ],
            [
                'name' => 'Valeria',
                'apellidos' => 'Hernández Navarro',
                'fecha_nacimiento' => '1997-04-12',
                'fecha_inicio_gestacion' => '2023-06-11',
                'peso_kg' => 59.9,
                'altura_cm' => 163.0,
                'rol' => 'gestante',
                'email' => 'valeria.hernandez@example.com',
                'password' => Hash::make('12345678'),
                'centro_id' => 1, // Asigna un ID de centro existente
            ],
            [
                'name' => 'Camila',
                'apellidos' => 'Jiménez Torres',
                'fecha_nacimiento' => '1999-08-25',
                'fecha_inicio_gestacion' => '2023-07-18',
                'peso_kg' => 57.5,
                'altura_cm' => 161.0,
                'rol' => 'gestante',
                'email' => 'camila.jimenez@example.com',
                'password' => Hash::make('12345678'),
                'centro_id' => 3, // Asigna un ID de centro existente
            ],
            [
                'name' => 'Paula',
                'apellidos' => 'Ruiz Vargas',
                'fecha_nacimiento' => '1992-06-03',
                'fecha_inicio_gestacion' => '2023-08-29',
                'peso_kg' => 63.0,
                'altura_cm' => 168.0,
                'rol' => 'gestante',
                'email' => 'paula.ruiz@example.com',
                'password' => Hash::make('12345678'),
                'centro_id' => 2, // Asigna un ID de centro existente
            ],
            [
                'name' => 'Daniela',
                'apellidos' => 'Castro Mendoza',
                'fecha_nacimiento' => '2000-02-14',
                'fecha_inicio_gestacion' => '2023-09-01',
                'peso_kg' => 56.2,
                'altura_cm' => 159.0,
                'rol' => 'gestante',
                'email' => 'daniela.castro@example.com',
                'password' => Hash::make('12345678'),
                'centro_id' => 1, // Asigna un ID de centro existente
            ],
            [
                'name' => 'Alejandra',
                'apellidos' => 'Ortiz Herrera',
                'fecha_nacimiento' => '1991-12-01',
                'fecha_inicio_gestacion' => '2023-10-14',
                'peso_kg' => 61.8,
                'altura_cm' => 167.0,
                'rol' => 'gestante',
                'email' => 'alejandra.ortiz@example.com',
                'password' => Hash::make('12345678'),
                'centro_id' => 3, // Asigna un ID de centro existente
            ],
        ];

        foreach ($gestantes as $gestante) {
            User::create($gestante);
        }
    }
}
