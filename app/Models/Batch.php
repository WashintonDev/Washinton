<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $table = 'batch';
    protected $primaryKey = 'batch_id';
    protected $fillable = ['code', 'batch_name', 'status', 'requested_at'];

    // Método para obtener los productos relacionados
    public function products()
    {
        return $this->hasMany(ProductBatch::class, 'batch_id'); // Relación con ProductBatch
    }
}
