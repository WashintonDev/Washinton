<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $primaryKey = 'inventory_id';
    protected $fillable = ['product_id', 'warehouse_id', 'store_id', 'stock', 'Reserved_Stock'];

    // Relación con producto
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relación con almacén
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    // Relación con tienda
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
