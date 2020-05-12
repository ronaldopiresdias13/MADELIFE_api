<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orcamento extends Model
{
    protected $guarded = [];

    public function historicos(){
        return $this->hasMany('App\Historicoorcamento');
    }
}
