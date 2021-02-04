<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrcamentoServico extends Model
{
    protected $table = 'orcamento_servico';
    protected $guarded = [];

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}