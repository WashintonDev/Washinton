<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseTransferDetail extends Model
{
    use HasFactory;

    protected $table = 'warehouse_transfer_detail';
    protected $primaryKey = 'transfer_detail_id';
    protected $fillable = ['transfer_id', 'product_id', 'quantity'];

    // Relación con la transferencia de almacén
    public function transfer()
    {
        return $this->belongsTo(WarehouseTransfer::class, 'transfer_id');
    }

    // Relación con el producto
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
