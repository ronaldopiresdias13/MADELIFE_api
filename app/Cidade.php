<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $guarded = [];

    public function endereco()
    {
        return $this->hasMany('App\Endereco', 'cidadade_id')->where('ativo', true);
    }
}
