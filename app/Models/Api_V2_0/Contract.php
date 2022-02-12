<?php

namespace App\Models\Api_V2_0;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $table = 'ml_contracts';

    protected $fillable = [
        'company_id',
        'budgets_id',
        'cliente_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budgets_id');
    }
}
