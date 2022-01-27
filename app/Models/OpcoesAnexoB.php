<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpcoesAnexoB extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'opcoes_anexo_b';

    protected $fillable = [
        'anexo_b_id', 'categoria','descricao_value_2','value','descricao_value','value_real'
    ];

    protected $guarded = [];

    public function anexo_b(){
        return $this->belongsTo(PlanilhaAnexoB::class,'anexo_b_id','id');
    }
}
