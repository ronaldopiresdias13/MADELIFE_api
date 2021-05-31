<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdemservicoPrestador extends Model
{
    protected $table = 'ordemservico_prestador';
    protected $guarded = [];

    public function prestador()
    {
        return $this->belongsTo(Prestador::class);
    }

    public function ordemservico()
    {
        return $this->belongsTo(Ordemservico::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
