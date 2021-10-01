<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicamentoPil extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'medicamentos_pil';

    protected $fillable = [
        'pil_id', 'medicamento_id','frequencia','via'
    ];

    protected $guarded = [];

    public function pil(){
        return $this->belongsTo(PlanilhaPil::class,'pil_id','id');
    }

    public function medicamento(){
        return $this->belongsTo(Produto::class,'medicamento_id','id');
    }

    public function horarios(){
        return $this->hasMany(HorarioMedicamentoPil::class,'medicamento_pil_id','id');
    }
}
