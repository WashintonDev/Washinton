<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Cambia Model a Authenticatable
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable // Cambia Model a Authenticatable
{
    use HasFactory, HasApiTokens; // Añade HasApiTokens

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'phone', 'role', 'location_type', 'status', 'store_id', 'firebase_user_ID'];

    // Relación con tienda
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    // Opcional: ocultar el campo de contraseña
    protected $hidden = ['password'];
}
