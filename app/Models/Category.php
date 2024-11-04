<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    protected $primaryKey = 'category_id';
    protected $fillable = ['name', 'description', 'parent_id'];

    // Relación para subcategorías
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Relación para la categoría padre
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relación con productos
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
