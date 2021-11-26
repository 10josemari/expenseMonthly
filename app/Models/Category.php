<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Tabla a la que apunta dicho modelo
    protected $table = "category";

    // Campos que se podrán rellenar al hacer insert o updates
    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at',
        'deleted_at'
    ];    
}
