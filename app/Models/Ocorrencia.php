<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $table = 'ocorrencias';

    protected $fillable = [
        'tipo', 'situacao','transcricao_produto_id','horario','empresa_id','justificativa','paciente_id','responsavel_id'
    ];

    protected $guarded = [];

    // public function escala()
    // {
    //     return $this->belongsTo(Escala::class,'escala_id','id');
    // }
    
    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function transcricao_produto()
    {
        return $this->belongsTo(TranscricaoProduto::class,'transcricao_produto_id','id');
    }

    public function pessoas()
    {
        return $this->belongsToMany(Pessoa::class, 'ocorrencias_pessoas', 'ocorrencia_id', 'pessoa_id');
    }

    public function chamados()
    {
        return $this->hasMany(Chamado::class, 'ocorrencia_id', 'id');
    }

    public function paciente()
    {
        return $this->belongsTo(Pessoa::class,'paciente_id','id');
    }

    public function responsavel()
    {
        return $this->belongsTo(Pessoa::class,'responsavel_id','id');
    }


    public function escalas()
    {
        return $this->belongsToMany(Escala::class, 'ocorrencias_escalas', 'ocorrencia_id', 'escala_id');
    }
}
