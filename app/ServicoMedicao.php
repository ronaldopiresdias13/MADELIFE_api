<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicoMedicao extends Model
{
    protected $table = 'servico_medicoes';
    protected $guarded = [];

    public function servico()
    {
        return $this->belongsTo('App\Servico');
    }
}
