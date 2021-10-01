<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnexoBInformacoes extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'anexo_b_informacoes';

    protected $fillable = [
        'anexo_b_id', 'categoria','value','telefone','cep','rua','numero','bairro','cidade','estado','complemento'
    ];

    protected $guarded = [];

    public function anexo_b(){
        return $this->belongsTo(PlanilhaAnexoB::class,'anexo_b_id','id');
    }
}
