<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryBank extends Model
{
    use HasFactory;

    // Tabla a la que apunta dicho modelo
    protected $table = "salary_bank";

    // Campos que se podrán rellenar al hacer insert o updates
    protected $fillable = [
        'bank_previous_month',
        'bank_adding_savings',
        'bank_now_total',
        'salary_id',
        'created_at',
        'updated_at'
    ];  
}
