<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        DB::table('supplier')->insert([
            ['name' => 'Procter & Gamble', 'description' => 'Empresa multinacional que ofrece productos de higiene personal y limpieza del hogar.', 'email' => 'contacto@pg.com', 'phone' => '5551234567', 'status' => 'active'],
            ['name' => 'Unilever', 'description' => 'Compañía global que produce artículos de cuidado personal y productos de limpieza.', 'email' => 'info@unilever.com', 'phone' => '5552345678', 'status' => 'active'],
            ['name' => 'Colgate-Palmolive', 'description' => 'Fabricante de productos de higiene bucal, cuidado personal y limpieza del hogar.', 'email' => 'ventas@colpal.com', 'phone' => '5553456789', 'status' => 'active'],
            ['name' => 'Kimberly-Clark', 'description' => 'Empresa dedicada a productos de higiene personal y profesional.', 'email' => 'servicio@kcc.com', 'phone' => '5554567890', 'status' => 'active'],
            ['name' => 'Henkel', 'description' => 'Compañía que ofrece detergentes y productos de limpieza para el hogar.', 'email' => 'atencion@henkel.com', 'phone' => '5555678901', 'status' => 'active'],
            ['name' => 'Reckitt Benckiser', 'description' => 'Fabricante de productos de salud, higiene y limpieza.', 'email' => 'contacto@rb.com', 'phone' => '5556789012', 'status' => 'active'],
            ['name' => 'Clorox', 'description' => 'Empresa especializada en productos de limpieza y desinfección.', 'email' => 'info@clorox.com', 'phone' => '5557890123', 'status' => 'active'],
            ['name' => 'SC Johnson', 'description' => 'Compañía que produce productos de limpieza y cuidado del aire.', 'email' => 'ventas@scjohnson.com', 'phone' => '5558901234', 'status' => 'active'],
            ['name' => 'Ecolab', 'description' => 'Proveedor de soluciones y servicios de limpieza y desinfección.', 'email' => 'servicio@ecolab.com', 'phone' => '5559012345', 'status' => 'active'],
            ['name' => 'Grupo AlEn', 'description' => 'Empresa mexicana que ofrece productos de limpieza para el hogar.', 'email' => 'contacto@grupoalen.com', 'phone' => '5550123456', 'status' => 'active'],
            ['name' => 'Fábrica de Jabón La Corona', 'description' => 'Productor de jabones y detergentes en México.', 'email' => 'info@lacorona.com', 'phone' => '5551234568', 'status' => 'active'],
            ['name' => 'Lysol', 'description' => 'Marca reconocida por sus productos desinfectantes y de limpieza.', 'email' => 'atencion@lysol.com', 'phone' => '5552345679', 'status' => 'active'],
            ['name' => 'Frosch', 'description' => 'Marca alemana de productos de limpieza ecológicos.', 'email' => 'ventas@frosch.com', 'phone' => '5553456780', 'status' => 'active'],
            ['name' => 'Ecover', 'description' => 'Empresa que produce productos de limpieza sostenibles.', 'email' => 'servicio@ecover.com', 'phone' => '5554567891', 'status' => 'active'],
            ['name' => 'Seventh Generation', 'description' => 'Fabricante de productos de limpieza y cuidado personal ecológicos.', 'email' => 'contacto@seventhgen.com', 'phone' => '5555678902', 'status' => 'active'],
            ['name' => 'Nala', 'description' => 'Marca mexicana de productos de limpieza sustentables y biodegradables.', 'email' => 'info@nala.mx', 'phone' => '5556789013', 'status' => 'active'],
            ['name' => 'Jabones Beltrán', 'description' => 'Empresa española dedicada a la fabricación de jabones tradicionales.', 'email' => 'atencion@jabonesbeltran.com', 'phone' => '5557890124', 'status' => 'active'],
            ['name' => 'Attitude', 'description' => 'Compañía canadiense que ofrece productos de higiene personal y limpieza ecológicos.', 'email' => 'ventas@attitude.com', 'phone' => '5558901235', 'status' => 'active'],
            ['name' => 'Almacabio', 'description' => 'Empresa italiana que produce detergentes y productos de limpieza ecológicos.', 'email' => 'servicio@almacabio.com', 'phone' => '5559012346', 'status' => 'active'],
            ['name' => 'Method', 'description' => 'Marca estadounidense de productos de limpieza ecológicos y de diseño.', 'email' => 'contacto@methodhome.com', 'phone' => '5550123457', 'status' => 'active'],
        ]);
    }
}
