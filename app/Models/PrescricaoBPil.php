<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrescricaoBPil extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'prescricoes_b_pil';

    protected $fillable = [
        'pil_id', 'cuidado_id','status'
    ];

    protected $guarded = [];

    public function pil(){
        return $this->belongsTo(PlanilhaPil::class,'pil_id','id');
    }

    public function cuidado(){
        return $this->belongsTo(Cuidado::class,'cuidado_id','id');
    }

    public function diagnosticos_secundarios()
    {
        return $this->belongsToMany(DiagnosticoPil::class, 'prescricoes_b_diag_sec_pil',  'prescricao_id','diagnostico_secundario_id');
    }
}
