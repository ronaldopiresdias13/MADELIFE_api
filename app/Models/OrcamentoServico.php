<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrcamentoServico extends Model
{
    protected $table = 'orcamento_servico';
    // protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'id',
        'orcamento_id',
        'servico_id',
        'quantidade',
        'frequencia',
        'basecobranca',
        'valorunitario',
        'custo',
        'custodiurno',
        'custonoturno',
        'subtotal',
        'subtotalcusto',
        'adicionalnoturno',
        'horascuidado',
        'horascuidadodiurno',
        'horascuidadonoturno',
        'icms',
        'inss',
        'iss',
        'descricao',
        'valorcustomensal',
        'valorresultadomensal',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
