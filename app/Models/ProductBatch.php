<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    use HasFactory;

    protected $table = 'product_batch'; // Nombre de la tabla
    protected $primaryKey = 'product_batch_id'; // Clave primaria
    protected $fillable = ['batch_id', 'product_id', 'quantity', 'expiration_date', 'status']; // Campos que se pueden llenar en masa

    // Método para la relación con el producto
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // Relación con Product
    }

    // Método para la relación con el lote
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id'); // Relación con Batch
    }
}
