<?php

namespace App\Models\Api_V2_0;

use App\Models\Servico;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionsServices extends Model
{
    use HasFactory;
    protected $table = 'ml_additions_services';

    protected $fillable = [
        'ml_additions_id', 'servico_id', 'quantity', 'billing_basis', 'frequency', 'unitary_value',
        'subtotal', 'cost', 'day_cost', 'night_cost', 'subtotal_cost', 'monthly_result_value', 'monthly_cost_value',
        'day_care_hours', 'hours_night_care'
    ];

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }
}
