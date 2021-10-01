<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EscalaBradenAnexoA extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'escalas_braden_aa';

    protected $fillable = [
        'anexo_a_id', 'categoria','pontuacao','value'
    ];

    protected $guarded = [];

    public function anexo_a(){
        return $this->belongsTo(PlanilhaAnexoA::class,'anexo_a_id','id');
    }
}
