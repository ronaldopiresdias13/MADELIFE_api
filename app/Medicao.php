<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicao extends Model
{
    protected $table = 'medicoes';
    protected $guarded = [];

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }
    public function ordemservico()
    {
        return $this->belongsTo('App\Ordemservico');
    }
    public function medicao_servicos()
    {
        return $this->hasMany('App\ServicoMedicao', 'medicoes_id')->where('ativo', true);
    }
}
