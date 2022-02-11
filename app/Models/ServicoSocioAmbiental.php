<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicoSocioAmbiental extends Model
{
    use HasFactory, Uuid,SoftDeletes;

    protected $table = 'servicos_socio_ambiental';
    public $incrementing = false;

    protected $fillable = [
        'telefone','nome', 'rua','cep','rua','numero','bairro','cidade','estado','complemento','empresa_id','tipo'
    ];

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function planilhas_anexo_b()
    {
        return $this->belongsToMany(PlanilhaAnexoB::class, 'servicos_and_socios_ambiental', 'servico_id','anexo_b_id');
    }
}
