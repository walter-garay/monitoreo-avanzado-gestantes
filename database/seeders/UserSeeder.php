<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Marta Ríos',
            'rol' => 'admin',
            'email' => 'marta.rios@example.com',
            'password' => Hash::make('marta.rios@example.com'),
        ]);

        User::create([
            'name' => 'Carla Pérez',
            'rol' => 'admin',
            'email' => 'carla.perez@example.com',
            'password' => Hash::make('carla.perez@example.com'),
        ]);

        User::create([
            'name' => 'Elena Díaz',
            'rol' => 'staff',
            'email' => 'elena.diaz@example.com',
            'password' => Hash::make('elena.diaz@example.com'),
        ]);

        User::create([
            'name' => 'Diego Castro',
            'rol' => 'admin',
            'email' => 'diego.castro@example.com',
            'password' => Hash::make('diego.castro@example.com'),
        ]);

        User::create([
            'name' => 'Lucía Navarro',
            'rol' => 'gestante',
            'email' => 'lucia.navarro@example.com',
            'password' => Hash::make('lucia.navarro@example.com'),
        ]);

        User::create([
            'name' => 'Pedro Sánchez',
            'rol' => 'staff',
            'email' => 'pedro.sanchez@example.com',
            'password' => Hash::make('pedro.sanchez@example.com'),
        ]);
    }

}
