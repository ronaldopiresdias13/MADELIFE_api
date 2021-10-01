<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HorarioMedicamentoPil extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'horarios_medicamentos_pil';

    protected $fillable = [
        'pil_id', 'medicamento_pil_id','horario'
    ];

    protected $guarded = [];

    public function pil(){
        return $this->belongsTo(PlanilhaPil::class,'pil_id','id');
    }

    public function medicamento(){
        return $this->belongsTo(MedicamentoPil::class,'medicamento_pil_id','id');
    }
    
}
