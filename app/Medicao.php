<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicao extends Model
{
    protected $table = 'medicoes';
    protected $guarded = [];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function ordemservico()
    {
        return $this->belongsTo(Ordemservico::class);
    }
    public function medicao_servicos()
    {
        return $this->hasMany(ServicoMedicao::class, 'medicoes_id')->where('ativo', true);
    }
}
