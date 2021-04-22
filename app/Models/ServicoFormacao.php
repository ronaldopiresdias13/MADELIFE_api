<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicoFormacao extends Model
{
    protected $table = 'servico_formacao';
    protected $guarded = [];

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
