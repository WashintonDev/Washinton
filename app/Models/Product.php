<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'product_id';
    protected $fillable = ['name', 'sku', 'description', 'price', 'status', 'image', 'category_id', 'supplier_id', 'type'];

    // Relación con categoría
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relación muchos-a-muchos con proveedores
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier', 'product_id', 'supplier_id');
    }

    // Relación con inventario
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'product_id');
    }

    // Relación con batches de productos
    public function batches()
    {
        return $this->hasMany(ProductBatch::class, 'product_id');
    }
}
