<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockRequestDetail extends Model
{
    use HasFactory;

    protected $table = 'restock_request_detail';
    protected $primaryKey = 'request_detail_id';
    protected $fillable = ['restock_request_id', 'product_id', 'quantity'];

    // Relación con la solicitud de reabastecimiento
    public function restockRequest()
    {
        return $this->belongsTo(RestockRequest::class, 'restock_request_id');
    }

    // Relación con el producto
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
