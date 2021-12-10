<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    // Tabla a la que apunta dicho modelo
    protected $table = "salary";

    // Campos que se podrán rellenar al hacer insert o updates
    protected $fillable = [
        'month',
        'year',
        'money',
        'saveMonthly',
        'config_id',
        'passed',
        'created_at',
        'updated_at'
    ];     
}
