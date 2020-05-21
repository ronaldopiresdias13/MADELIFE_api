<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orcamentoservico extends Model
{
    protected $table = 'orcamentoservicos';
    protected $guarded = [];

    public function servico(){
        return $this->belongsTo('App\Servico');
    }
}
