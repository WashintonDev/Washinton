<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    use HasFactory;

    protected $table = 'product_batch';
    protected $primaryKey = 'batch_id';
    protected $fillable = ['product_id', 'quantity', 'received_date', 'expiration_date', 'status'];

    // Relación con producto
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
