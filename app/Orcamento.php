<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orcamento extends Model
{
    protected $guarded = [];

    public function historicos(){
        return $this->hasMany('App\Historicoorcamento');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }

    public function servicos()
    {
        return $this->belongsToMany('App\Servico', 'orcamento_servico');//->withPivot('orcamento_id', 'servico_id');
    }

    public function produtos()
    {
        return $this->belongsToMany('App\Produto', 'orcamento_produto');
    }

    public function custos()
    {
        return $this->hasMany('App\Custo');
    }

    public function orcamento_servico()
    {
        return $this->hasMany('App\OrcamentoProduto');
    }
}
