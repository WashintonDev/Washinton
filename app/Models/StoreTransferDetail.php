<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreTransferDetail extends Model
{
    use HasFactory;

    protected $table = 'store_transfer_detail';
    protected $primaryKey = 'transfer_detail_id';

    protected $fillable = [
        'store_transfer_id',
        'product_id',
        'quantity',
        'status',
    ];

    public function storeTransfer()
    {
        return $this->belongsTo(StoreTransfer::class, 'store_transfer_id', 'store_transfer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}