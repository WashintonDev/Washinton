<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    use HasFactory;

    protected $table = 'product_supplier';
    public $timestamps = false; // Si no tienes campos de timestamp en la tabla
    protected $primaryKey = null; // Indica que esta tabla no tiene una clave primaria en el sentido tradicional
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'supplier_id'
    ];

    // Relación con Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relación con Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
