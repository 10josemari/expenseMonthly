<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalarySubtractedTotal extends Model
{
    use HasFactory;

    // Tabla a la que apunta dicho modelo
    protected $table = "salary_subtracted_total";

    // Campos que se podrán rellenar al hacer insert o updates
    protected $fillable = [
        'name',
        'amount',
        'salary_id',
        'created_at',
        'updated_at'
    ]; 
}
