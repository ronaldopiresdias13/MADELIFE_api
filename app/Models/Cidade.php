<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $guarded = [];

    public function endereco()
    {
        return $this->hasMany(Endereco::class, 'cidadade_id')->where('ativo', true);
    }
}
