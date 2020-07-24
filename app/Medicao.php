<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicao extends Model
{
    protected $table = 'medicoes';
    protected $guarded = [];

    public function medicao_servicos()
    {
        return $this->hasMany('App\ServicoMedicao', 'medicoes_id')->where('ativo', true);
    }
}
