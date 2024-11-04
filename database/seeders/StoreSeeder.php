<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    public function run()
    {
        DB::table('store')->insert([
            [
                'name' => 'Tienda Tijuana Centro',
                'phone' => '6641234567',
                'address' => 'Av. Revolución 123, Zona Centro',
                'status' => 'active',
                'city' => 'Tijuana',
                'state' => 'Baja California',
            ],
            [
                'name' => 'Tienda Ensenada Norte',
                'phone' => '6467654321',
                'address' => 'Calle Primera 456, Zona Norte',
                'status' => 'active',
                'city' => 'Ensenada',
                'state' => 'Baja California',
            ],
            [
                'name' => 'Tienda Mexicali Valle',
                'phone' => '6869876543',
                'address' => 'Blvd. Lázaro Cárdenas 789, Col. Valle',
                'status' => 'active',
                'city' => 'Mexicali',
                'state' => 'Baja California',
            ],
            [
                'name' => 'Tienda Tecate Este',
                'phone' => '6652345678',
                'address' => 'Av. Hidalgo 321, Col. Centro',
                'status' => 'active',
                'city' => 'Tecate',
                'state' => 'Baja California',
            ],
            [
                'name' => 'Tienda Rosarito Playa',
                'phone' => '6618765432',
                'address' => 'Blvd. Benito Juárez 654, Zona Playa',
                'status' => 'active',
                'city' => 'Playas de Rosarito',
                'state' => 'Baja California',
            ],
        ]);
    }
}
