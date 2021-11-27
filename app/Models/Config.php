<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    // Tabla a la que apunta dicho modelo
    protected $table = "config";

    // Campos que se podrán rellenar al hacer insert o updates
    protected $fillable = [
        'option',
        'value',
        'created_at',
        'updated_at'
    ];      
}
