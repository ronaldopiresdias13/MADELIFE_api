<?php

namespace App\Models\Api_V2_0;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionsExtension extends Model
{
    use HasFactory;
    protected $table = 'ml_additions_extension';

    protected $fillable = [
        'start_date', 'end_date', 'paciente_id', 'additions_id'
    ];
}
