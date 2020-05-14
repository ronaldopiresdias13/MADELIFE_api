<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $guarded = [];

    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }
}
