<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExameFisicoAnexoA extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'exame_fisico_anexo_a';

    protected $fillable = [
        'anexo_a_id', 'categoria','descricao_value_2','value','descricao_value'
    ];

    protected $guarded = [];

    public function anexo_a(){
        return $this->belongsTo(PlanilhaAnexoA::class,'anexo_a_id','id');
    }
}
