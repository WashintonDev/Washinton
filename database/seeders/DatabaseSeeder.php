<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llama a cada seeder para poblar las tablas
        $this->call([
            CategorySeeder::class,
            SupplierSeeder::class,
            WarehouseSeeder::class,
            StoreSeeder::class,
            ProductSeeder::class,
            ProductSupplierSeeder::class,
            // Agrega aquí otros seeders si los tienes
        ]);
    }
}
