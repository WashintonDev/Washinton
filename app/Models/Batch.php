<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $table = 'batch'; // Nombre de la tabla
    protected $primaryKey = 'batch_id'; // Clave primaria
    protected $fillable = ['batch_name', 'status', 'requested_date']; // Campos que se pueden llenar en masa

    // Método para obtener los productos relacionados
    public function products()
    {
        return $this->hasMany(ProductBatch::class, 'batch_id'); // Relación con ProductBatch
    }
}
