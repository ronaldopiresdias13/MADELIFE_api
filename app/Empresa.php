<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $guarded = [];

    public function clientes()
    {
        return $this->hasMany('App\Cliente');
    }
}
