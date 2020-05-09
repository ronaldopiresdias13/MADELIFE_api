<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $guarded = [];

    public function cliente()
    {
        return $this->hasMany('App\Cliente');
    }
}
