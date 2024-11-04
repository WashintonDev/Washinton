<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'phone', 'role', 'location_type', 'status', 'store_id'];

    // Relación con tienda
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
