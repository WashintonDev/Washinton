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
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'location_type',
        'status',
        'store_id',
        'role_id', // Asegúrate de incluir role_id aquí
        'firebase_user_ID',
    ];
    

    // Relación con tienda
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    // Opcional: ocultar el campo de contraseña
    protected $hidden = ['password'];

    // Añade esta relación al modelo User  puesta para tratar de solucionar login 
public function role()
{
    return $this->belongsTo(Role::class, 'role_id');
}

}
