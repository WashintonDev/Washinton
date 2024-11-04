<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        DB::table('warehouse')->insert([
            [
                'name' => 'Almacén TJ Principal',
                'phone' => '6640552154',
                'status' => 'active',
            ]
        ]);
    }
}
