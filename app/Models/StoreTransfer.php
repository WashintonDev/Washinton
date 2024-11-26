<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreTransfer extends Model
{
    use HasFactory;

    protected $table = 'store_transfer';
    protected $primaryKey = 'store_transfer_id';

    protected $fillable = [
        'store_id',
        'store_transfer_name',
        'status',
        'requested_at',
        'received_date',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function details()
    {
        return $this->hasMany(StoreTransferDetail::class, 'store_transfer_id', 'store_transfer_id');
    }
}