<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialActivity extends Model
{
    use HasFactory;

    // Tabla a la que apunta dicho modelo
    protected $table = "financial_activity";

    // Campos que se podrán rellenar al hacer insert o updates
    protected $fillable = [
        'month',
        'year',
        'name',
        'type',
        'value',
        'user_id',
        'category_id',
        'created_at',
        'updated_at'
    ];     
}
