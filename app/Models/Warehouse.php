<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouse';
    protected $primaryKey = 'warehouse_id';
    protected $fillable = ['name', 'phone', 'status'];

    // Relación con inventario
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'warehouse_id');
    }
}
