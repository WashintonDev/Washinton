<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseTransfer extends Model
{
    use HasFactory;

    protected $table = 'warehouse_transfer';
    protected $primaryKey = 'transfer_id';
    protected $fillable = ['store_id', 'transfer_date', 'status'];

    // Relación con la tienda
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    // Relación con los detalles de la transferencia
    public function details()
    {
        return $this->hasMany(WarehouseTransferDetail::class, 'transfer_id');
    }
}
