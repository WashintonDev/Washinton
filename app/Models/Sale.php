<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sale';
    protected $primaryKey = 'sale_id';
    protected $fillable = ['store_id', 'sale_date', 'total_amount'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }

    // Obtener productos a través de los detalles de venta
    public function products()
{
    return $this->belongsToMany(Product::class, 'sale_detail', 'sale_id', 'product_id');
}
}
