<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiggyBank extends Model
{
    use HasFactory;

    // Tabla a la que apunta dicho modelo
    protected $table = "piggy_bank";

    // Campos que se podrán rellenar al hacer insert o updates
    protected $fillable = [
        'option',
        'amount',
        'created_at',
        'updated_at'
    ]; 
}
