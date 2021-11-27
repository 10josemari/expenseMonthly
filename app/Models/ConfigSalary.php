<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigSalary extends Model
{
    use HasFactory;

    // Tabla a la que apunta dicho modelo
    protected $table = "config_salary";

    // Campos que se podrán rellenar al hacer insert o updates
    protected $fillable = [
        'bank_previous_month',
        'bank_adding_savings',
        'bank_now_total',
        'month',
        'year',
        'salary_id',
        'config_id',
        'created_at',
        'updated_at'
    ]; 
}
