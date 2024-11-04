<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('category')->insert([
            ['name' => 'Higiene y cuidado personal', 'description' => 'Productos para el cuidado e higiene personal', 'parent_id' => null],
            ['name' => 'Higiénicos', 'description' => 'Productos para la higiene del hogar y superficies', 'parent_id' => null],
            ['name' => 'Jarcería', 'description' => 'Herramientas y productos de limpieza', 'parent_id' => null],
            ['name' => 'Limpieza', 'description' => 'Productos específicos para limpieza profunda', 'parent_id' => null],
            
            ['name' => 'Jabones de barra', 'description' => 'Jabones para uso personal en formato de barra', 'parent_id' => 1],
            ['name' => 'Jabones líquidos', 'description' => 'Jabones para el cuerpo en formato líquido', 'parent_id' => 1],
            ['name' => 'Shampoos', 'description' => 'Productos para el lavado y cuidado del cabello', 'parent_id' => 1],
            ['name' => 'Acondicionadores', 'description' => 'Productos para suavizar y acondicionar el cabello', 'parent_id' => 1],
            ['name' => 'Desodorantes', 'description' => 'Productos para controlar el olor corporal', 'parent_id' => 1],
            ['name' => 'Cremas corporales', 'description' => 'Cremas hidratantes para el cuerpo', 'parent_id' => 1],
            ['name' => 'Protector solar', 'description' => 'Productos para protección contra rayos UV', 'parent_id' => 1],
            ['name' => 'Pastas dentales', 'description' => 'Productos para el cuidado e higiene bucal', 'parent_id' => 1],
            ['name' => 'Enjuagues bucales', 'description' => 'Productos de enjuague para higiene bucal', 'parent_id' => 1],
            ['name' => 'Hilo dental', 'description' => 'Hilo para limpieza interdental', 'parent_id' => 1],
            ['name' => 'Toallitas húmedas', 'description' => 'Toallitas para limpieza de la piel', 'parent_id' => 1],
            ['name' => 'Perfumes y colonias', 'description' => 'Fragancias para uso personal', 'parent_id' => 1],
            ['name' => 'Gel antibacterial', 'description' => 'Producto para desinfección de manos sin agua', 'parent_id' => 1],
            ['name' => 'Jabón íntimo', 'description' => 'Jabones diseñados para higiene íntima', 'parent_id' => 1],

            ['name' => 'Desinfectantes', 'description' => 'Productos para desinfección de superficies', 'parent_id' => 2],
            ['name' => 'Limpiadores multiusos', 'description' => 'Limpiadores para todo tipo de superficies', 'parent_id' => 2],
            ['name' => 'Limpiavidrios', 'description' => 'Productos para limpieza de vidrios y espejos', 'parent_id' => 2],
            ['name' => 'Desengrasantes', 'description' => 'Productos para eliminar grasa en superficies', 'parent_id' => 2],
            ['name' => 'Aromatizantes', 'description' => 'Ambientadores y neutralizadores de olores', 'parent_id' => 2],
            ['name' => 'Insecticidas', 'description' => 'Productos para control de insectos', 'parent_id' => 2],
            ['name' => 'Blanqueadores', 'description' => 'Productos blanqueadores para superficies', 'parent_id' => 2],
            ['name' => 'Limpia pisos', 'description' => 'Productos para limpiar pisos y superficies', 'parent_id' => 2],
            ['name' => 'Jabón para ropa', 'description' => 'Jabón en polvo y líquido para lavado de ropa', 'parent_id' => 2],
            ['name' => 'Suavizantes de ropa', 'description' => 'Suavizantes para telas y ropa', 'parent_id' => 2],
            ['name' => 'Desincrustantes', 'description' => 'Productos para remover manchas difíciles', 'parent_id' => 2],
            ['name' => 'Limpia alfombras', 'description' => 'Productos específicos para alfombras', 'parent_id' => 2],
            ['name' => 'Limpia baños', 'description' => 'Productos específicos para sanitarios y baños', 'parent_id' => 2],
            ['name' => 'Desodorantes ambientales', 'description' => 'Productos para ambientación de espacios', 'parent_id' => 2],

            ['name' => 'Escobas', 'description' => 'Escobas de distintos materiales para barrer', 'parent_id' => 3],
            ['name' => 'Trapeadores', 'description' => 'Instrumentos para limpiar y trapear pisos', 'parent_id' => 3],
            ['name' => 'Recogedores', 'description' => 'Herramientas para recoger residuos', 'parent_id' => 3],
            ['name' => 'Cepillos de barrer', 'description' => 'Cepillos para limpieza de pisos y exteriores', 'parent_id' => 3],
            ['name' => 'Cepillos de mano', 'description' => 'Cepillos para limpieza detallada', 'parent_id' => 3],
            ['name' => 'Esponjas de limpieza', 'description' => 'Esponjas para limpieza de cocina y hogar', 'parent_id' => 3],
            ['name' => 'Estropajos', 'description' => 'Estropajos metálicos y de fibra para fregar', 'parent_id' => 3],
            ['name' => 'Fibras abrasivas', 'description' => 'Fibras duras para limpieza de suciedad difícil', 'parent_id' => 3],
            ['name' => 'Plumeros', 'description' => 'Plumeros de microfibra y materiales sintéticos', 'parent_id' => 3],
            ['name' => 'Toallas de microfibra', 'description' => 'Toallas para limpieza sin rayas', 'parent_id' => 3],
            ['name' => 'Cubetas', 'description' => 'Cubetas para agua y otros líquidos de limpieza', 'parent_id' => 3],
            ['name' => 'Guantes de limpieza', 'description' => 'Guantes para protección en tareas de limpieza', 'parent_id' => 3],
            ['name' => 'Mopas', 'description' => 'Mopas para pisos de diferentes materiales', 'parent_id' => 3],
            ['name' => 'Paños de limpieza', 'description' => 'Paños multiusos para diferentes superficies', 'parent_id' => 3],

            ['name' => 'Limpieza de cocina', 'description' => 'Productos para desinfección y limpieza de cocina', 'parent_id' => 4],
            ['name' => 'Desinfectantes de alimentos', 'description' => 'Productos para desinfección de frutas y verduras', 'parent_id' => 4],
            ['name' => 'Lavalozas', 'description' => 'Jabones y productos para lavar platos', 'parent_id' => 4],
            ['name' => 'Detergentes', 'description' => 'Detergentes para lavado de ropa y superficies', 'parent_id' => 4],
            ['name' => 'Desinfectantes en aerosol', 'description' => 'Aerosoles para desinfección de espacios', 'parent_id' => 4],
            ['name' => 'Limpiadores de acero', 'description' => 'Productos para superficies de acero inoxidable', 'parent_id' => 4],
            ['name' => 'Limpiadores de madera', 'description' => 'Productos para limpieza de superficies de madera', 'parent_id' => 4],
            ['name' => 'Limpiadores de cerámica', 'description' => 'Productos para baldosas y cerámica', 'parent_id' => 4],
            ['name' => 'Pulidores de muebles', 'description' => 'Productos para pulir y mantener muebles', 'parent_id' => 4],
            ['name' => 'Pulidores de piso', 'description' => 'Pulidores para mantenimiento de pisos', 'parent_id' => 4],
            ['name' => 'Limpieza de electrodomésticos', 'description' => 'Productos para limpieza de electrodomésticos', 'parent_id' => 4],
            ['name' => 'Limpiadores antigrasa', 'description' => 'Productos para eliminar grasa de superficies', 'parent_id' => 4],
            ['name' => 'Detergentes industriales', 'description' => 'Detergentes para uso en limpieza industrial', 'parent_id' => 4],
            ['name' => 'Limpiadores desinfectantes', 'description' => 'Limpiadores con propiedades desinfectantes', 'parent_id' => 4],
        ]);
    }
}
