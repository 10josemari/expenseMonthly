<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySavings extends Model
{
    use HasFactory;

    // Tabla a la que apunta dicho modelo
    protected $table = "monthly_savings";

    // Campos que se podrán rellenar al hacer insert o updates
    protected $fillable = [
        'month',
        'year',
        'value',
        'user_id',
        'config_id',
        'created_at',
        'updated_at'
    ];       
}
