<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $guarded = [];

    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }
}
