<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    protected $guarded = [];

    public function cidades(){
        return $this->hasOne('App\Cidade');
    }
}
