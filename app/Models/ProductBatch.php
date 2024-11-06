<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    use HasFactory;

    protected $table = 'product_batches';
    protected $primaryKey = 'product_batch_id';
    protected $fillable = ['batch_id', 'product_id', 'quantity', 'received_date', 'expiration_date', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}