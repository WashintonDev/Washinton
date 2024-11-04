<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'store';
    protected $primaryKey = 'store_id';
    protected $fillable = ['name', 'phone', 'address', 'status', 'city', 'state'];

    // Relación con usuarios
    public function users()
    {
        return $this->hasMany(User::class, 'store_id');
    }

    // Relación con inventario
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'store_id');
    }

    // Relación con ventas
    public function sales()
    {
        return $this->hasMany(Sale::class, 'store_id');
    }
}
