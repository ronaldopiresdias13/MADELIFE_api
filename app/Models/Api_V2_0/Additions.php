<?php

namespace App\Models\Api_V2_0;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Additions extends Model
{
    use HasFactory;
    protected $table = 'ml_additions';

    protected $fillable = [
        'start_date', 'end_date', 'additionscol', 'contracts_id'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contracts_id');
    }
    public function ml_produtos()
    {
        return $this->hasMany(AdditionsProducts::class, 'ml_additions_id');
    }
    public function ml_servicos()
    {
        return $this->hasMany(AdditionsServices::class, 'ml_additions_id');
    }
    public function ml_extension()
    {
        return $this->hasOne(AdditionsExtension::class, 'additions_id');
    }

}
