<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockRequest extends Model
{
    use HasFactory;

    protected $table = 'restock_request';
    protected $primaryKey = 'restock_request_id';
    protected $fillable = ['supplier_id', 'request_date', 'status'];

    // Relación con el proveedor
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Relación con los detalles de la solicitud de reabastecimiento
    public function details()
    {
        return $this->hasMany(RestockRequestDetail::class, 'restock_request_id');
    }
}
