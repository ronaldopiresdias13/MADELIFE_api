<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendaSaida extends Model
{
    // use Uuid;

    // protected $keyType = 'string';
    // protected $primaryKey = 'uuid';
    protected $table = 'venda_saida';
    protected $guarded = [];

    public function venda()
    {
        return $this->hasOne(Venda::class)->where('ativo', true);
    }
}
