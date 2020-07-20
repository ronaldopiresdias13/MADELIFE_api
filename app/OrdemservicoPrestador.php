<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdemservicoPrestador extends Model
{
    protected $table = 'ordemservico_prestador';
    protected $guarded = [];

    public function prestador()
    {
        return $this->belongsTo('App\Prestador');
    }
}
